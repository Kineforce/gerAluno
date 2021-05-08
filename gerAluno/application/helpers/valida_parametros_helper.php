<?php

/**
 * Helper com o objetivo de validar se os parâmetros no corpo do request são os corretos
 */
function parameterValidation($required,$postvalues){

	try{
		$missing = array();

		foreach($required as $field) {
			if(!isset($postvalues[$field])) {
				$missing[] = $field;
			}
		}

		// Caso o request venha com mais parâmetros que o necessário
		if(count($postvalues) > count($required)){
			return 'parametros_excedidos';
		}
		
		if(count($missing)>=1) {
			$count = (count($missing)>=2?'are':'is');
			return implode(", ", $missing).'_'.$count.'_required';
		}
		else {
			return 'validado';
	} 

	// Para caso haja algum erro na montagem do JSON no request
	} catch (TypeError $e){
		return 'estrutura_parametros_incorreta';
	}

	
}
