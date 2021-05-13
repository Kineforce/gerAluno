<?php

/**
 * Helper com o objetivo de validar se os parâmetros no corpo do request são os corretos
 */
function parameterValidation($required,$postvalues){

	try{
		
		$missing = array();

		// Verifica se o valor não existir no request, incrementar no array $missing
		foreach($required as $field) {
			if(!isset($postvalues[$field])) {
				$missing[] = $field;
			}
		}

		// Caso o request venha com mais parâmetros que o necessário
		if(count($postvalues) > count($required)){
			return [
				"error" => "Parâmetros excedidos"
			];
		}
		
		if(count($missing)>=1) {
			return $json = [
				"error" => array(
					"missing_parameters" => array(
						implode(", ", $missing)
					)
				)
			];
		}

		else {
			return [
				"success" => "Operação efetuada"
			];
	} 

	// Para caso haja algum erro na montagem do JSON no request
	} catch (TypeError $e){
		return [
			"error" => "Estrutura de dados do request está incorreta"
		];
	}

	
}
