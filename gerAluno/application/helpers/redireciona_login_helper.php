<?php
/**
 * Redireciona de acordo com o nome da rota fornecida
 */
function customRedir($viewName){

    header("Location: ".BASE_URL()."$viewName");

}
