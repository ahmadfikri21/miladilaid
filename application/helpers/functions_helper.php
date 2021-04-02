<?php
    function fieldErrorCheck($fieldName){
        return (form_error("$fieldName") != null ? 'fieldError' : '');
    }