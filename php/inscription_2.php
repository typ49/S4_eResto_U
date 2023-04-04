<?php

function error_password() {
    echo '<h4>Error -> Password</h4>',
    '<p>password and verification are not the same !</p>';
}

function error_empty() {
    echo '<h4>Error -> Empty fields</h4>',
    '<p>some fields of the form are empty !</p>';
}

function error_mail() {
    echo '<h4>Error -> Wrong email syntaxe</h4>',
    '<p>the email who sent was incorrect !</p>';
}