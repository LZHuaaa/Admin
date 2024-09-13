<?php

// ============================================================================
// PHP Setups
// ============================================================================

date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

// ============================================================================
// General Page Functions
// ============================================================================

// Is GET request?
function is_get()
{
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// Is POST request?
function is_post()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// Obtain GET parameter
function get($key, $value = null)
{
    $value = $_GET[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain POST parameter
function post($key, $value = null)
{
    $value = $_POST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain REQUEST (GET and POST) parameter
function req($key, $value = null)
{
    $value = $_REQUEST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Redirect to URL
function redirect($url = null)
{
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}

// Set or get temporary session variable
function temp($key, $value = null)
{
    if ($value !== null) {
        $_SESSION["temp_$key"] = $value;
    } else {
        $value = $_SESSION["temp_$key"] ?? null;
        unset($_SESSION["temp_$key"]);
        return $value;
    }
}

// ============================================================================
// HTML Helpers
// ============================================================================

// Encode HTML special characters
function encode($value)
{
    return htmlentities($value);
}

// Generate <input type='text'>
function html_text($key, $label, $attr = '')
{
    $value = htmlspecialchars($GLOBALS[$key] ?? '', ENT_QUOTES);
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <input type='text' id='$key' name='$key' value='$value' $attr class='form-control'>
          </div>";
}

function html_text2($key, $label, $value = '', $attr = '')
{
    $value = htmlspecialchars($value, ENT_QUOTES);
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <input type='text' id='$key' name='$key' value='$value' $attr class='form-control'>
          </div>";
}


//password
function html_password($key, $label, $pattern = '', $title = '', $attr = '')
{
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <input type='password' id='$key' name='$key' pattern='$pattern' title='$title' $attr class='form-control' required>
          </div>";
}

function html_password1($id, $name, $label, $attr = '')
{
    echo "<div class='form-group'>
            <label for='$id'>$label</label>
            <input type='password' id='$id' name='$name' class='form-control' $attr>
          </div>";
}

//email
function html_email($key, $label, $title = '', $attr = '')
{
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <input type='email' id='$key' name='$key' title='$title' $attr class='form-control' required>
          </div>";
}


function html_email2($key, $label, $value, $title = '', $attr = '')
{
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <input type='email' id='$key' name='$key'value='$value' title='$title' $attr class='form-control' required>
          </div>";
}
//birthday
function html_birthdate($key, $label, $value, $title = '', $attr = '')
{
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <input type='date' id='$key' name='$key' value='$value' title='$title' $attr class='form-control' required>
          </div>";
}



//dropdown
function html_select($key, $label, $options = [], $selectedValue = '', $attr = '')
{
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <select id='$key' name='$key' $attr class='form-control'>";

    foreach ($options as $value => $display) {
        $selected = ($selectedValue == $value) ? 'selected' : '';
        echo "<option value='$value' $selected>$display</option>";
    }

    echo "</select></div>";
}


//file upload
function html_file($key, $label, $value, $attr = '')
{
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <input type='file' id='$key' value='$value' name='$key' $attr class='form-control'>
          </div>";
}



// Generate <input type='search'>
function html_search($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='search' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='radio'> list
function html_radios($key, $items, $br = false)
{
    $value = encode($GLOBALS[$key] ?? '');
    echo '<div>';
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'checked' : '';
        echo "<label><input type='radio' id='{$key}_$id' name='$key' value='$id' $state>$text</label>";
        if ($br) {
            echo '<br>';
        }
    }
    echo '</div>';
}



// Generate table headers <th>
function table_headers($fields, $sort, $dir, $href = '')
{
    foreach ($fields as $k => $v) {
        $d = 'asc'; // Default direction
        $c = '';    // Default class

        if ($k == $sort) {
            $d = $dir == 'asc' ? 'desc' : 'asc';
            $c = $dir;
        }

        echo "<th><a href='?sort=$k&dir=$d&$href' class='$c'>$v</a></th>";
    }
}

//hidden
function html_hidden($key, $value)
{
    $value = htmlspecialchars($value, ENT_QUOTES);
    echo "<input type='hidden' name='$key' id='$key' value='$value'>";
}
//submit
function html_submit($id, $value, $class = 'btn-success')
{
    echo "<button type='submit' id='$id' class='$class'>$value</button>";
}



// ============================================================================
// Error Handlings
// ============================================================================

// Global error array
$_err = [];

// Generate <span class='err'>
function err($key)
{
    global $_err;
    if ($_err[$key] ?? false) {
        echo "<span class='err'>$_err[$key]</span>";
    } else {
        echo '<span></span>';
    }
}

// ============================================================================
// Database Setups and Functions
// ============================================================================

// Global PDO object
$_db = new PDO('mysql:dbname=happyfitness', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

// Is unique?
function is_unique($value, $table, $field)
{
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() == 0;
}

// Is exists?
function is_exists($value, $table, $field)
{
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() > 0;
}

// ============================================================================
// Global Constants and Variables
// ============================================================================

$_genders = [
    'F' => 'Female',
    'M' => 'Male',
];

function get_file($key)
{
    $f = $_FILES[$key] ?? null;

    if ($f && $f['error'] == 0) {
        return (object)$f;
    }

    return null;
}

// Crop, resize and save photo
function save_photo($f, $folder, $width = 200, $height = 200)
{
    $photo = uniqid() . '.jpg';

    require_once 'lib/SimpleImage.php';
    $img = new SimpleImage();
    $img->fromFile($f->tmp_name)
        ->thumbnail($width, $height)
        ->toFile("$folder/$photo", 'image/jpeg');

    return $photo;
}

function html_number($key, $min = '', $max = '', $step = '', $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='number' id='$key' name='$key' value='$value'
                 min='$min' max='$max' step='$step' $attr>";
}



// Is money?
function is_money($value)
{
    return preg_match('/^\-?\d+(\.\d{1,2})?$/', $value);
}
