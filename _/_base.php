<?php

// ============================================================================
// PHP Setups
// ============================================================================
date_default_timezone_set('Asia/Kuala_Lumpur');
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

// Is integer?
function isInteger($value) {
    if (is_numeric($value)) {
        return ((int)$value == $value);
    }
    return false;
}

// Is email?
function is_email($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

// Initialize and return mail object
function get_mail() {
    global $s_mail_host;
    global $s_mail_port;
    global $s_mail_username;
    global $s_mail_password;
    global $s_mail_name;
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
    $key = 'sk_test_51ORvmlBZ0phwb5Fpe7Rq7VmnFlp5VRCc6prMsrXzu3zV6VowA4dEebasCnQ7daHC53fyIj7m5CPbLQnJRagywklP00B81RpDBn';
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

//  Generate <select> - order status
function selectStatus($key, $items, $value = null, $default = true, $attr = '') {
    $value ??= encode($GLOBALS[$key] ?? '');
    
    echo "<select id='$key' name='$key' $attr class='form-select ps-2' onchange='disableOptions(this);'>";
    
    if ($default) {
        echo "<option value=''>- Select One -</option>";
    }
    
    foreach ($items as $id => $name) {
        $state = $id == $value ? 'selected' : '';
        $disabled = ($value >= 1 && $id <2 ) ? 'disabled' : ''; // Disable if "Cancelled" is selected or a status lower than "Preparing"
        
        echo "<option value='$id' $state $disabled>$name</option>";
    }
    
    echo "</select>";
}


// Generate <select>
function select($key, $items, $value = null, $default = true, $attr = '') {
    $value ??= encode($GLOBALS[$key] ?? '');
    echo "<select id='$key' name='$key' $attr >";
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

// Generate <input type='month'>
function month($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='month' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='date'>
function _date($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='date' id='$key' name='$key' value='$value' $attr>";
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
    get_user($user->id,true);
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
    $uri = base("/login.php");
    redirect($uri);
}

function get_user($id,$save=false){
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
    if ($save) {
        $_SESSION['user'] = $u;
    }

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

function add_cart($id, $unit) {
    $cart = get_cart();
    $count = $cart[$id];
    $unit = $count + $unit;

    if ($unit >= 1 && $unit <= 10 && is_exists($id, 'products', 'product_id')) {
        $cart[$id] = $unit;
    }
    else {
        unset($cart[$id]);
    }

    set_cart($cart);
}

// Update shopping cart
function update_cart($id, $unit) {
    $cart = get_cart();

    if ($unit >= 1 && $unit <= 10 && is_exists($id, 'products', 'product_id')) {
        $cart[$id] = $unit;
    }
    else {
        unset($cart[$id]);
    }

    set_cart($cart);
}


// Remove shopping cart
function remove_from_cart($product_id) {
    $cart = get_cart();
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        set_cart($cart);
    }
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


// TODO
function get_featured_products(){
    global $db;
    $featured_products = $db->query('SELECT product_id FROM products ORDER BY product_id ASC LIMIT 4');
    $fp = array();
    foreach ($featured_products as $p){
        $fp[] = get_product($p->product_id);
    }
    
    return $fp;
}


// add to favourite
function get_favourite($u=null){
    global $db;
    global $user;
    $u = $u ?? $user;
    
    return $db->query("SELECT product_id FROM favourite_products WHERE user_id = $u->id")->fetchAll(PDO::FETCH_COLUMN);
}

// get featured products
function featured_products($product=null){
    $product = $product ?? get_featured_products();
    $fav_p = get_favourite();

    foreach ($product as $p){
        $photo = $p->photos[0];
        echo "<div class='product text-center col-lg-3 col-md-4 col-sm-12' >";
        if (in_array($p->product_id,$fav_p)){
            echo "<i data-fav='$p->product_id' class='red fa-solid fa-heart' style='z-index:100;'></i>";
        }else{
            echo "<i data-fav='$p->product_id' class='red fa-regular fa-heart' style='z-index:100;'></i>";
        }
        $uri = base("products/single_product.php?product_id=$p->product_id");
        echo "<a href='$uri'>
            <img src='../_/photos/products/$photo' alt='' class='img-fluid mb-3'>
            <div class='star'>
                <i class='fas fa-star'></i>
                <i class='fas fa-star'></i>
                <i class='fas fa-star'></i>
                <i class='fas fa-star'></i>
                <i class='fas fa-star'></i>
            </div>
            <h5 class='p-name'>$p->product_name</h5>
            <h4 class='p-price'>RM$p->product_price</h4>
            <button class='buy-btn'>Buy Now</button>
            </a>
      </div>";
    }
    
}


function get_product($id){
    global $db;

    $stm = $db->prepare(
        "SELECT * 
        FROM products 
        WHERE product_id = ?
    ");

    $stm->execute([$id]);
    $p = $stm->fetch();

    if(!$p){
        return null;
    }

    $stm = $db->query("SELECT photo FROM product_pic WHERE id = '$id'");
    $rows = $stm -> fetchAll();
    $p->photos = array();
    foreach($rows as $row) {
        $p->photos[] = $row->photo;
    }
    return $p;
}
function get_products($ids=null){
    global $db;

    if($ids){
        $in = in($ids);
        $stm = $db->prepare(
            "SELECT * 
            FROM products WHERE product_id IN ($in)
        ");
        $stm->execute($ids);
        $arr = $stm->fetchAll();
    }else{
        $stm = $db->query(
            "SELECT * 
            FROM products
        ");
        $arr = $stm->fetchAll();
    }

    foreach($arr as $p){
        $stm = $db->query("SELECT photo FROM product_pic WHERE id = '$p->product_id'");
        $rows = $stm -> fetchAll();
        $p->photos = array();
        foreach($rows as $row) {
            $p->photos[] = $row->photo;
        }
    }
    return $arr;
}
// ============================================================================
// Lookup Tables
// ============================================================================

$_states = [
    'JHR' => "Johor",
    'KDH' =>"Kedah",
    'KTN' =>"Kelantan",
    'MLK' =>"Melaka",
    'NSN' =>"Negeri Sembilan",
    'PHG' => "Pahang",
    'PRK' => "Perak",
    'PLS' => "Perlis",
    'PNG' => "Pulau Pinang",
    'SWK' => "Sarawak",
    'SGR' => "Selangor",
    'TRG' => "Terengganu",
    'KUL' => "Kuala Lumpur",
    'LBN' => "Labuan",
    'SBH' => "Sabah",
    'PJY' => "Putrajaya"
];

$_orderStatus = [
    0 => 'Pending',
    1 => 'Preparing',
    2 => 'Completed',
];


$_categories = $db->query('SELECT category_id, category_name FROM categories')->fetchAll(PDO::FETCH_KEY_PAIR);
// ============================================================================
// Global Variables and Constants
// ============================================================================

$_title = 'Untitled';
$_head  = '';
$_foot  = '';