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

function html_textarea($name, $label, $value = '', $placeholder = '', $required = false, $rows = 4, $cols = 50)
{
    $requiredAttr = $required ? 'required' : '';
    echo '
    <div class="form-group">
        <label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>
        <textarea name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" 
                  rows="' . intval($rows) . '" cols="' . intval($cols) . '" 
                  placeholder="' . htmlspecialchars($placeholder) . '" ' . $requiredAttr . ' 
                  class="form-control">' . htmlspecialchars($value) . '</textarea>
    </div>';
}



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

function html_birthdate($key, $label, $value, $title = '', $attr = '')
{
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <input type='date' id='$key' name='$key' value='$value' title='$title' $attr class='form-control' required>
          </div>";
}




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

function html_select_category($name, $label, $options, $selected = '', $required = false)
{
    $requiredAttr = $required ? 'required' : '';

    echo '
    <div class="form-group">
        <label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>
        <select name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" ' . $requiredAttr . ' class="form-control">';

    foreach ($options as $option) {
        $value = htmlspecialchars($option['categoryid']);
        $display = htmlspecialchars($option['categoryname']);
        $isSelected = ($value == $selected) ? ' selected' : '';
        echo '<option value="' . $value . '"' . $isSelected . '>' . $display . '</option>';
    }

    echo '
        </select>
    </div>';
}


function html_select_category2($name, $label, $options, $selected, $required = false)
{
    $requiredAttr = $required ? 'required' : '';

    echo '
    <div class="form-group">
        <label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>
        <select name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" ' . $requiredAttr . ' class="form-control">';

    foreach ($options as $option) {
        $value = htmlspecialchars($option['categoryID']);
        $display = htmlspecialchars($option['categoryName']);
        $isSelected = ($value == $selected) ? ' selected' : '';
        echo '<option value="' . $value . '"' . $isSelected . '>' . $display . '</option>';
    }

    echo '
        </select>
    </div>';
}

function html_select_promotion($name, $label, $promotions, $selected = '', $required = false)
{
    $requiredAttr = $required ? 'required' : '';

    echo '<div class="form-group">';
    echo '<label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>';
    echo '<select name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" ' . $requiredAttr . ' class="form-control">';
    echo '<option value=" ">No Promotion</option>';
    foreach ($promotions as $promo) {
        $isSelected = ($promo['promotionid'] == $selected) ? ' selected' : '';
        echo '<option value="' . htmlspecialchars($promo['promotionid']) . '"' . $isSelected . '>' . htmlspecialchars($promo['name']) . '</option>';
    }
    echo '</select>';
    echo '</div>';
}


function html_select_promotion2($name, $label, $promotions, $selected, $required = false)
{
    $requiredAttr = $required ? 'required' : '';

    echo '<div class="form-group">';
    echo '<label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>';
    echo '<select name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" ' . $requiredAttr . ' class="form-control">';
    echo '<option value=" ">No Promotion</option>';
    foreach ($promotions as $promo) {
        $isSelected = ($promo['promotionID'] == $selected) ? ' selected' : '';
        echo '<option value="' . htmlspecialchars($promo['promotionID']) . '"' . $isSelected . '>' . htmlspecialchars($promo['name']) . '</option>';
    }
    echo '</select>';
    echo '</div>';
}


function html_file($key, $label, $value, $attr = '')
{
    echo "<div class='form-group'>
            <label for='$key'>$label</label>
            <input type='file' id='$key' value='$value' name='$key' $attr class='form-control'>
          </div>";
}


function html_file_multiple($name, $label, $attributes = '')
{
    echo '
    <div class="form-group">
        <label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>
        <input type="file" name="' . htmlspecialchars($name) . '[]" id="' . htmlspecialchars($name) . '" ' . $attributes . ' class="form-control">
    </div>';
}

function html_txt_upload($name, $label, $attributes = '') {
    echo '
    <div class="form-group">
        <label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>
        <input type="file" name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" ' . $attributes . ' class="form-control" accept=".txt">
    </div>';
}


function html_search($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='search' id='$key' name='$key' value='$value' $attr>";
}


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

function html_select_size_type()
{
    echo '
    <div class="form-group">
    <label for="sizeType">Select Size Type:</label>
    <select id="sizeType" name="sizeType" onchange="showSizes()" style="border: 1px solid black; padding: 5px; border-radius: 4px; width:200px;">
        <option value="">--Select Size Type--</option>
        <option value="no_size">No Size</option>
        <option value="standard">Standard Sizes (Clothes)</option>
        <option value="shoe">Shoe Sizes (UK)</option>
    </select>
    </div>

<div class="form-group">
    <div id="standardSizes" style="display:none;">
        <label>Select Standard Sizes:</label>
        <input type="checkbox" name="sizes[]" value="S"> S
        <input type="checkbox" name="sizes[]" value="M"> M
        <input type="checkbox" name="sizes[]" value="L"> L
        <input type="checkbox" name="sizes[]" value="XL"> XL
    </div>
    </div>

    <div class="form-group">
    <div id="shoeSizes" style="display:none;">
        <label>Select Shoe Sizes (UK):</label>
        <input type="checkbox" name="sizes[]" value="UK5"> UK 5
        <input type="checkbox" name="sizes[]" value="UK6"> UK 6
        <input type="checkbox" name="sizes[]" value="UK7"> UK 7
        <input type="checkbox" name="sizes[]" value="UK8"> UK 8
        <input type="checkbox" name="sizes[]" value="UK9"> UK 9
    </div>
    </div>
    ';
}

function html_size_selector($productSizes = [])
{
    echo'  <div class="form-group">';
    echo '<label for="sizeType">Select Size Type:</label>';
    echo '<select id="sizeType" name="sizeType" onchange="showSizes()" style="border: 1px solid black; padding: 5px; border-radius: 4px; width:200px;">';
    echo '<option value="">--Select Size Type--</option>';
    echo '<option value="standard">Standard Sizes (Clothes)</option>';
    echo '<option value="shoe">Shoe Sizes (UK)</option>';
    echo '<option value="none">No Size</option>';
    echo '</select>';
    echo '</div>';

    echo ' <div class="form-group">';
    echo '<div id="standardSizes" style="display:none;">';
    echo '<label>Select Standard Sizes:</label>';
    echo '<input type="checkbox" name="sizes[]" value="S" ' . (in_array('S', $productSizes) ? 'checked' : '') . '> S ';
    echo '<input type="checkbox" name="sizes[]" value="M" ' . (in_array('M', $productSizes) ? 'checked' : '') . '> M ';
    echo '<input type="checkbox" name="sizes[]" value="L" ' . (in_array('L', $productSizes) ? 'checked' : '') . '> L ';
    echo '<input type="checkbox" name="sizes[]" value="XL" ' . (in_array('XL', $productSizes) ? 'checked' : '') . '> XL ';
    echo '</div>';
    echo '</div>';

    echo ' <div class="form-group">';
    echo '<div id="shoeSizes" style="display:none;">';
    echo '<label>Select Shoe Sizes (UK):</label>';
    echo '<input type="checkbox" name="sizes[]" value="UK5" ' . (in_array('UK5', $productSizes) ? 'checked' : '') . '> UK 5 ';
    echo '<input type="checkbox" name="sizes[]" value="UK6" ' . (in_array('UK6', $productSizes) ? 'checked' : '') . '> UK 6 ';
    echo '<input type="checkbox" name="sizes[]" value="UK7" ' . (in_array('UK7', $productSizes) ? 'checked' : '') . '> UK 7 ';
    echo '<input type="checkbox" name="sizes[]" value="UK8" ' . (in_array('UK8', $productSizes) ? 'checked' : '') . '> UK 8 ';
    echo '<input type="checkbox" name="sizes[]" value="UK9" ' . (in_array('UK9', $productSizes) ? 'checked' : '') . '> UK 9 ';
    echo '</div>';
    echo '</div>';
}


function html_hidden($key, $value)
{
    $value = htmlspecialchars($value, ENT_QUOTES);
    echo "<input type='hidden' name='$key' id='$key' value='$value'>";
}
//submit
function html_submit($id, $value, $class = 'btn-success')
{

    echo "<br> <button type='submit' id='$id' class='$class'>$value</button>";
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

function html_number($name, $label, $attributes = '')
{
    echo '
    <div class="form-group">
        <label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>
        <input type="number" name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" ' . $attributes . ' class="form-control">
    </div>';
}

function html_textarea2($name, $label, $value = '', $attributes = '')
{
    echo '<div class="form-group">';
    echo '<label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>';
    echo '<textarea name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" ' . $attributes . ' class="form-control">' . htmlspecialchars($value) . '</textarea>';
    echo '</div>';
}

// Function to generate a number input
function html_number2($name, $label, $value = '', $attributes = '')
{
    echo '<div class="form-group">';
    echo '<label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>';
    echo '<input type="number" name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" ' . $attributes . ' class="form-control">';
    echo '</div>';
}

function html_youtube_link($name, $label, $url, $required = false) {
    $requiredAttr = $required ? 'required' : '';
    
    echo "
    <div class='form-group'>
        <label for='$name'>$label</label>
        <input type='url' name='$name' id='$name' value='" . htmlspecialchars($url) . "'
               placeholder='https://youtube/...'
               class='form-control' $requiredAttr>
    </div>
    ";
    
    if (!empty($url)) { 
        echo "
        <div class='youtube-embed'>
            <iframe width='400' height='300' src='" . htmlspecialchars($url) . "' frameborder='0' allowfullscreen></iframe>
        </div>
        ";
    }
}



/*function html_file_video($name, $label, $attributes = '') {
    echo '<div class="form-group">';
    echo '<label for="' . $name . '">' . $label . '</label>';
    echo '<input type="file" name="' . $name . '" id="' . $name . '" ' . $attributes . ' class="form-control">';
    echo '</div>';
}*/

function html_date($name, $label, $attributes = '', $value = '') {
    
    if (!$value) {
        $value = date('Y-m-d'); 
    }
    
    echo '<div class="form-group">';
    echo '<label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($label) . '</label>';
    echo '<input type="date" name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" ' . $attributes . ' class="form-control"> ';
    echo '</div>';
}


// Is money?
function is_money($value)
{
    return preg_match('/^\-?\d+(\.\d{1,2})?$/', $value);
}
