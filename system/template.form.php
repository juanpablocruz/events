<?php
/*
 * This is a template class which creates and manages a HTML form creating its fields from a provided database table
 * and then collecting and inserting the data in it.
 * 
 *  -------------------------------------------------------------*
 * 
 * There are two ways of calling this class, by passing an argument "true" or not.
 * Passing the argument allows you to customize the form because it creates an tabename.html with the html of the 
 * form. Feel free to change the object type or input type aswell as adding classes, or modifing values or placeholders
 * but in order to be able to insert those inputs into their correspondent database field, DO NOT change their names.
 * 
 *  -------------------------------------------------------------*
 * 
 * the public function formulario, takes three parameters: $user_table which stands for the desired database table from
 * where to take the fields to create the inputs.
 * $omitidos which is an array of the database table fields which you don't want to be in the form or want to process manually like
 * ids.its default is NULL.
 * $user_pass_field is a string which indicates the name of the field of the password, to change its input type from text to password.
 * its default is NULL.
 * 
 *  -------------------------------------------------------------*
 * 
 * the public function insert() takes no argument, it just calls the private function fetch_data which gets the submited data from the form via
 * post, and then returns it in a string, and then inserts it into the diferent tables used.
 * 
 *  -------------------------------------------------------------*
 * 
 * Usage:
 * In the form page.
 *  
 * $omitidos = array ('UserId','Omited_Field');
 * $form = new TemplateForm();
 * echo '<form action='post.php' method='post'>;
 * $form->formulario('table',$omitidos,'Password');
 * echo '<input type='submit' value='submit' />;
 * echo '</form>
 * 
 * In the form action page.
 * 
 * require 'form page.php';
 * $form->insert();
 * 
 * -------------------------------------------------------------*
 * 
 * @author		Juan Pablo Cruz <pablo@cruzf.net>
 * @copyright (c) 2012 Juan Pablo Cruz 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */
interface form
{
	public function formulario($user_table,array $omitidos = NULL,$user_pass_field = NULL);
	public function insert();
}
class TemplateForm implements form
{
	private $fields;
	private $campos;
	private $tablas;
	private $num_tablas;
	private $datos;
	private $omitidos;
	private $editable;
	private $form;
	
	public function __construct($edit = FALSE){
		$this->editable = FALSE;
		if($edit) $this->editable = TRUE;
		$this->num_tablas = 1;
		$this->tablas = array ($this->num_tablas);
		$this->form='';
	}
	
	private function createForm($db,$user_table,$omitidos,$user_pass_field){
		$i = 0;
		$tempform = '';
		while($field = mysql_fetch_field($db)){
			$this->fields[$i] = $field->name;
			$i++;
			switch($field->name){
				case $user_pass_field:
					$tempform.= "<input type='password' placeholder='password' name='password'/>";
					break;
				default:
					if(!in_array($field->name,$omitidos)){
							if($field->type == 'int' && $field->zerofill)
							{
							$tempform.= "<span><input type='checkbox' value=1 name=".$field->name." id=".$field->name." /><label for=".$field->name.">".$field->name."</label></span>";
							}
							else{
							$tempform.= "<input type='text' placeholder=".$field->name." name=".$field->name." id=".$field->name." />";
							}
						}
					break;
			}	
		}
		return $tempform;
	}
	private function editableForm($db,$user_table,$omitidos,$user_pass_field){
		$formulario=$this->createForm($db,$user_table,$omitidos,$user_pass_field);
		$FileName = $user_table.".html";
		if(!file_exists($FileName)){
		$ourFileHandle = fopen($FileName, 'w') or die("can't open file");
		fwrite($ourFileHandle, $formulario);
		fclose($ourFileHandle);
		}
		require $FileName;
	}
	
	public function formulario($user_table,array $omitidos = NULL,$user_pass_field = NULL){
		$qry = "SELECT * FROM ".$user_table;
		$db = mysql_query ($qry);
		
		$this->tablas[$this->num_tablas-1] = $user_table;
		$this->num_tablas++;
		$this->campos = mysql_num_fields($db);
		$this->fields = array ($this->campos);
		$this->omitidos = $omitidos;
		
		if(!$this->editable) $this->form=$this->createForm($db,$user_table,$omitidos,$user_pass_field);
		else $this->editableForm($db,$user_table,$omitidos,$user_pass_field);
		
		$this->datos[$this->num_tablas-1] = ($this->fields);
		$this->fields = '';
		mysql_free_result($db);
		
		echo $this->form;
	}
	private function fetch_data($fields){
		$values = '';
		foreach($fields as $campo){
			if(!in_array($campo,$this->omitidos)){
				if(isset($_POST[$campo])){
					$valor = $_POST[$campo];
					if($valor == '1')$valor = 1;
					}
				else{
					$valor = 0;
				}
			$type = gettype($valor);
			if($type != 'integer')
				$values .= "'".$valor."',";
			else{
			$values .= $valor.",";
				}
			}
		}
		$values = substr($values ,0,-1);	
		return $values;
	}
	public function insert(){
		$j = 1;
		foreach($this->tablas as $campo){
			$insert_fields = array (sizeof($campo));
			$i = 0;
			foreach($this->datos[$j] as $dato){
				$insert_fields[$i] = $dato;
				$i++;
				}	
			$string = "";
			foreach($insert_fields as $field){
				if(!in_array($field,$this->omitidos)){
					$string.=$field.",";
				}
			}
			$string = substr($string ,0,-1);			
		$values=$this->fetch_data($this->datos[$j]);
		$query=("INSERT INTO ".$campo." (".$string.") VALUES (".$values.")");
		$result = mysql_query($query) or die($query);
		$j++;
		}
	}
    public function __sleep()
    {	
        return array('fields', 'campos', 'tablas', 'num_tablas','datos','omitidos','editable','form');
    }
    
    public function __wakeup()
    {
 
    }	
}

?>