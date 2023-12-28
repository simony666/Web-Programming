<?php

// ============================================================================
// PHP Setups
// ============================================================================

session_start();
require '_settings.php';

// ============================================================================
// General Functions
// ============================================================================

// Is GET request?
function is_get() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// Is POST request?
function is_post() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// Obtain GET parameter
function get($key, $default = null) {
    $value = $_GET[$key] ?? $default;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain POST parameter
function post($key, $default = null) {
    $value = $_POST[$key] ?? $default;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain REQUEST (GET and POST) parameter
function req($key, $default = null) {
    $value = $_REQUEST[$key] ?? $default;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain uploaded file --> cast to object
function get_file($key) {
    $f = $_FILES[$key] ?? null;
    
    if ($f && $f['error'] == 0) {
        return (object)$f;
    }

    return null;
}

// Redirect to URL
function redirect($url = null) {
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}

// Crop, resize and save photo
function save_photo($f, $folder, $width = 200, $height = 200) {
    $photo = uniqid() . '.jpg';
    
    require_once 'lib/SimpleImage.php';
    $img = new SimpleImage();
    $img->fromFile($f->tmp_name)
        ->thumbnail($width, $height)
        ->toFile("$folder/$photo", 'image/jpeg');

    return $photo;
}

// Is money?
function is_money($value) {
    return preg_match('/^\-?\d+(\.\d{1,2})?$/', $value);
}

// Is email?
function is_email($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

// Initialize and return mail object
function get_mail() {
    // Username = BAIT2173.email@gmail.com
    // Password = qopeyfvldofsizpp

    require_once 'lib/PHPMailer.php';
    require_once 'lib/SMTP.php';

    $m = new PHPMailer(true);
    $m->isSMTP();
    $m->SMTPAuth = true;
    $m->Host = $s_mail_host;
    $m->Port = $s_mail_port;
    $m->Username = $s_mail_username;
    $m->Password = $s_mail_password;
    $m->CharSet = 'utf-8';
    $m->setFrom($m->Username, $s_mail_name);

    return $m;
}

// Return local root path
function root($path = '') {
    return "$_SERVER[DOCUMENT_ROOT]/$path";
}

// Return base url (host + port)
function base($path = '') {
    return "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/$path";
}

// Initialize and return stripe client
function get_stripe() {
    $key = 'sk_test_51ON8CbCME2Wa4fbgxEC49szdqaTvekSlgjDuXe6frnjdWstNA81zEP2mF12d8f9KR4sdMHbr55ayX6pwj57HvqJB00vNXgz0YW';
    require_once 'lib/stripe/init.php';
    return new \Stripe\StripeClient($key);
}

// Return JSON data to client
function json($data) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($data,JSON_NUMERIC_CHECK);
    exit();
}

// ============================================================================
// HTML Helpers
// ============================================================================

// Encode HTML special characters
function encode($value) {
    return htmlentities($value);
}

// Generate <input type='text'>
function text($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='password'>
function password($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='password' id='$key' name='$key' value='$value' $attr>";
}

// generate <textarea>
function textarea($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<textarea id='$key' name='$key' $attr>$value</textarea>";
}

// generate SINGLE <input type='checkbox'>
function checkbox($key, $label = '', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    $status = $value == 1 ? 'checked' : '';
    echo "<label><input type='checkbox' id='$key' name='$key' value='1' $status $attr>$label</label>";
}

// Generate <input type='radio'> list
function radios($key, $items, $br = false) {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<div>";
    foreach ($items as $id => $name) {
        $state = $id == $value ? 'checked' : '';
        echo "<label><input type='radio' id='$key-$id' name='$key' value='$id' $state>$name</label>";
        if ($br) echo "<br>";
    }
    echo "</div>";
}

// Generate <select>
function select($key, $items, $value = null, $default = true, $attr = '') {
    $value ??= encode($GLOBALS[$key] ?? '');
    echo "<select id='$key' name='$key' $attr>";
    if ($default) {
        echo "<option value=''>- Select One -</option>";
    }
    foreach ($items as $id => $name) {
        $state = $id == $value ? 'selected' : '';
        echo "<option value='$id' $state>$name</option>";
    }
    echo "</select>";
}

// Generate <input type='file'>
function _file($key, $accept = '', $attr = '') {
    echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
}

// Generate <input type='hidden'>
function hidden($key, $value = null, $attr = '') {
    $value ??= encode($GLOBALS[$key] ?? '');
    echo "<input type='hidden' id='$key' name='$key' value='$value' $attr>";
}

// Generate table headers (th)
function table_headers($fields, $sort, $dir, $href = '') {
    foreach ($fields as $f) {
        $d = 'asc';
        $c = '';

        if ($f == $sort) {
            $d = $dir == 'asc' ? 'desc' : 'asc';
            $c = $dir;
        }

        $text = str_replace('_', ' ', $f);
        echo "<th><a href='?sort=$f&dir=$d&$href' class='$c'>$text</a></th>";
    }
}

// ============================================================================
// Errors
// ============================================================================

// Global $err array
$err = [];

// Generate <span class='err'>
function err($key) {
    global $err;
    if ($err[$key] ?? false) {
        echo "<span class='err'>$err[$key]</span>";
    }
    else {
        echo "<span></span>";
    }
}

// ============================================================================
// Temporary Data
// ============================================================================

// Read or set temporaly session variable
function temp($key, $value = null) {
    if ($value) {
        $_SESSION["temp-$key"] = $value;
    }
    else {
        $value = $_SESSION["temp-$key"] ?? null;
        unset($_SESSION["temp-$key"]);
        return $value;
    }
}

// ============================================================================
// Security
// ============================================================================

// Global $user object
$user = $_SESSION['user'] ?? null;

// Login user
function login($user, $url = '/') {
    unset($user->password);
    $_SESSION['user'] = $user;
    redirect($url);
}

// Logout user
function logout($url = '/') {
    unset($_SESSION['user']);
    redirect($url);
}

// Authorization
function auth(...$roles) {
    global $user;
    if ($user) {
        if ($roles) {
            if (in_array($user->role, $roles)) {
                return; // OK
            }
        }
        else {
            return; // OK
        }
    }
    
    redirect('/login.php');
}

function get_user($id){
    global $db;
    $stm = $db->prepare('SELECT * FROM user WHERE id = ?');
    $stm->execute([$id]);
    $u = $stm->fetch();
    
    $stm = $db->prepare("SELECT photo FROM profile_pic WHERE id = $id");
    $stm->execute([]);
    $rows = $stm -> fetchAll();
    $u->photos = array();
    foreach($rows as $row) {
        $u->photos[] = $row->photo;
    }

    unset($u->password);
    $_SESSION['user'] = $u;

    return $u;
}

// ============================================================================
// Shopping Cart
// ============================================================================

// Get shopping cart
function get_cart() {
    return $_SESSION['cart'] ?? [];
}

// Set shopping cart
function set_cart($cart = []) {
    $_SESSION['cart'] = $cart;
}

// Update shopping cart
function update_cart($id, $unit) {
    $cart = get_cart();

    if ($unit >= 1 && $unit <= 10 && is_exists($id, 'product', 'id')) {
        $cart[$id] = $unit;
    }
    else {
        unset($cart[$id]);
    }

    set_cart($cart);
}

// ============================================================================
// Database and Database Functions
// ============================================================================

// Generate IN clause
function in($arr) {
    return str_repeat('?,', count($arr)) . 'NULL';
}

// Is unique?
function is_unique($value, $table, $field) {
    global $db;
    $stm = $db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() == 0;
}

// Is exists?
function is_exists($value, $table, $field) {
    global $db;
    $stm = $db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() > 0;
}

// Global $db object
$db = new PDO("mysql:host=$s_db_host;port=$s_db_port;dbname=$s_db_database", "$s_db_user", "$s_db_password", [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

// ============================================================================
// Lookup Tables
// ============================================================================

// ============================================================================
// Global Variables and Constants
// ============================================================================

$_title = 'Untitled';
$_head  = '';
$_foot  = '';