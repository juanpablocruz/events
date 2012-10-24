					<?php
					$to = "pablo@cruzf.net";
					$subject = "Autentificación requerida";
					$body = "
					Bienvenido a e-Vents.es tu agenda social.\n
					Click <a href=''>here</a> to autentificate.
					";
					$from = "epifania21@gmail.com";
					if (mail($to, $subject, $body,"From: " . $from)) {
					   echo("<p>Message successfully sent!</p>");
					  } else {
					   echo("<p>Message delivery failed...</p>");
					  }
					  ?>