<?php

// Definimos contantes podemos utilizar define("NOMBRE", "valor");
// tambien podemos utilizar const NOMBRE="valor";

const SERVER = "localhost"; // Servidor de mysql
const USER = "root";  // Nombre de usuario de mysql
const PASSWORD = ""; // Contraseña de myqsl
const DB = "chinita"; // Nombre de la base de datos
const SECRET_KEY = 'SPLCH2024';

date_default_timezone_set('America/caracas');
// Create connection
// $conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }
