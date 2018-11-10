<?php
// Ver el ejemplo de password_hash() para ver de dónde viene este hash.
$clave="12";
$hashed_clave = password_hash($clave, PASSWORD_BCRYPT);
//$hashed_clave = '$2y$10$J7YRndagAABJoYp8nwvMAO9E2b38oi8JZtuMsc.Y9xp';


if (password_verify('12', $hashed_clave)) {
    echo '¡La contraseña es válida!';
} else {
    echo 'La contraseña no es válida.';
}
?>