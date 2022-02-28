<?php

/*
Plugin Name: Custom Cryptocurrency Converter
Description: The Custom Cryptocurrency Converter — is a easy-to-use with beautiful UI real-time web tool to conversion cryptocurrencies FOR ANY WEBSITES. Users can choose from available fiat Currencies and Cryptocurrencies.
Version: 1.0.0
Author: rapsum97
Author URI: https://www.fiverr.com/rapsum97?up_rollout=true
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

define('CCC_NAME', 'Custom Cryptocurrency Converter');
define('CCC_PATH', plugin_dir_path(__FILE__));
define('CCC_URL', plugin_dir_url(__FILE__));
define('CCC_PLUGIN_SLUG', 'custom-cryptocurrency-converter');

function CCC_add_styles() {
    wp_enqueue_script('jquery');
    wp_enqueue_style('wpb-google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap', false);
    wp_enqueue_style("CarouselCSS", "https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css", false);
    wp_register_script("jqueryJS", plugins_url("/assets/admin/js/jquery-2.2.4.js", __FILE__), array("jquery"));
    wp_enqueue_script("jqueryJS");
    wp_register_script("PopperJS", plugins_url("/assets/admin/js/popper.min.js", __FILE__), array("jquery"));
    wp_enqueue_script("PopperJS");
    wp_register_script("BootstrapJS", plugins_url("/assets/admin/js/jquery-2.2.4.js", __FILE__), array("jquery"));
    wp_enqueue_script("BootstrapJS");
    wp_register_script("customJS", plugins_url("/assets/admin/js/script.js", __FILE__), array("jquery"));
    wp_enqueue_script("customJS");
    wp_register_script("tableJS", plugins_url("/assets/datatables/js/jquery.dataTables.min.js", __FILE__), array("jquery"));
    wp_enqueue_script("tableJS");
    wp_register_script("tableJS2", plugins_url("/assets/datatables/js/dataTables.bootstrap4.min.js", __FILE__), array("jquery"));
    wp_enqueue_script("tableJS2");
    wp_register_style("customCSS", plugins_url("/assets/admin/css/style.css", __FILE__));
    wp_enqueue_style("customCSS");
    wp_register_style("bootstrapCSS", plugins_url("/assets/admin/css/bootstrap.min.css", __FILE__));
    wp_enqueue_style("bootstrapCSS");
    wp_register_style("Select2CSS", plugins_url("/assets/select2/css/select2.min.css", __FILE__));
    wp_enqueue_style("Select2CSS");
    wp_register_script("Select2JS", plugins_url("/assets/select2/js/select2.min.js", __FILE__), array("jquery"));
    wp_enqueue_script("Select2JS");
    wp_register_style("datatableCSS", plugins_url("/assets/datatables/css/dataTables.bootstrap4.min.css", __FILE__));
    wp_enqueue_style("datatableCSS");
    wp_enqueue_script("CarouselJS", "https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js", false);
}
add_action('wp_enqueue_scripts', 'CCC_add_styles');

/* Plugin Menu and Submenu */
add_action("admin_menu", "CryptoPlugin");
function CryptoPlugin() {
    $menu = add_menu_page("Cryptocurrency", "Cryptocurrency", 4, "crypto-management", "cryptoFiatsFunction");
    $submenu1 = add_submenu_page("crypto-management", "Crypto Fiats", "Crypto Fiats", 4, "crypto-management", "cryptoFiatsFunction");
    $submenu2 = add_submenu_page("crypto-management", "Commissions Rate", "Commissions Rate", 4, "crypto-commissions-form", "cryptoCommissionsFormFunction");
    $submenu3 = add_submenu_page("crypto-management", "WhatsApp Contact", "WhatsApp Contact", 4, "crypto-whatsapp-contact-form", "cryptoWhatsAppContactFormFunction");

    add_action('admin_print_styles-' . $menu, 'CCC_add_styles');
    add_action('admin_print_styles-' . $submenu1, 'CCC_add_styles');
    add_action('admin_print_styles-' . $submenu2, 'CCC_add_styles');
    add_action('admin_print_styles-' . $submenu3, 'CCC_add_styles');
}

// Database Creation
register_activation_hook( __FILE__, 'custom_crypto_converter_create_db');
function custom_crypto_converter_create_db() {
    global $wpdb;
    $version = get_option('custom_crypto_converter_version', '1.0.0');
    $charset_collate = $wpdb->get_charset_collate();
    $fiats_form_table = $wpdb->prefix.'custom_crypto_fiats_details';
    $commissions_form_table = $wpdb->prefix.'custom_crypto_commissions';
    $contact_form_table = $wpdb->prefix.'custom_crypto_contact';

    $sql = "CREATE TABLE $fiats_form_table (
        id int(11) NOT NULL AUTO_INCREMENT,
        fiats_name varchar(100) NOT NULL,
        fiats_full varchar(200) NOT NULL,
        active int(5) NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    $sql2 = "CREATE TABLE $commissions_form_table (
        id int(11) NOT NULL AUTO_INCREMENT,
        commissions_name varchar(50) NOT NULL,
        commissions_percentage varchar(100) NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    $sql3 = "CREATE TABLE $contact_form_table (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(50) NOT NULL,
        contact_number varchar(50) NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql2);
    dbDelta($sql3);
}

register_activation_hook(__FILE__, 'custom_crypto_converter_insert_data');
function custom_crypto_converter_insert_data() {
    global $wpdb;
    $table_name = $wpdb->prefix.'custom_crypto_fiats_details';
    $wpdb->insert($table_name, array('id' => '1', 'fiats_name' => 'BTC', 'fiats_full' => 'Bitcoin', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '2', 'fiats_name' => 'ETH', 'fiats_full' => 'Ethereum', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '3', 'fiats_name' => 'BCH', 'fiats_full' => 'Bitcoin Cash', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '4', 'fiats_name' => 'TRX', 'fiats_full' => 'Tron', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '5', 'fiats_name' => 'XMR', 'fiats_full' => 'Monero', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '6', 'fiats_name' => 'DASH', 'fiats_full' => 'DASH', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '7', 'fiats_name' => 'ZEC', 'fiats_full' => 'Zcash', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '8', 'fiats_name' => 'LTC', 'fiats_full' => 'Litecoin', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '9', 'fiats_name' => 'EOS', 'fiats_full' => 'EOS', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '10', 'fiats_name' => 'XRP', 'fiats_full' => 'Ripple', 'active' => '1'));
    $wpdb->insert($table_name, array('id' => '11', 'fiats_name' => 'ADA', 'fiats_full' => 'Cardano', 'active' => '1'));

    $table_name2 = $wpdb->prefix.'custom_crypto_commissions';
    $wpdb->insert($table_name2, array('id' => '1', 'commissions_name' => 'BUY', 'commissions_percentage' => '8'));
    $wpdb->insert($table_name2, array('id' => '2', 'commissions_name' => 'SELL', 'commissions_percentage' => '6'));

    $table_name3 = $wpdb->prefix.'custom_crypto_contact';
    $wpdb->insert($table_name3, array('id' => '1', 'name' => 'PHONE', 'contact_number' => '34641223061'));
}

/* Crypto Management Fiats */
function cryptoFiatsFunction() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $fiats_db = $prefix.'custom_crypto_fiats_details';
    $result = $wpdb->get_results("SELECT * FROM $fiats_db WHERE `fiats_name` <> ''");

    if (isset($_POST['newFiatSubmit'])) {
        $label = $_POST['newFiatLabel'];
        $label_full = $_POST['newFiatFull'];
        $wpdb->query("INSERT INTO $fiats_db (fiats_name, fiats_full, active) VALUES ('$label', '$label_full', '1')");
        echo "<script>alert('New Fiat Details Added Successfully!');</script>";
        echo "<script>location.replace('admin.php?page=crypto-management');</script>";
    }
    if (isset($_POST['updateFiatInfoSubmit'])) {
        $id = $_GET['updateFiatInfo'];
        $label = $_POST['updateFiatInfoLabel'];
        $label_full = $_POST['updateFiatInfoFull'];
        $wpdb->query("UPDATE $fiats_db SET fiats_name = '$label', fiats_full = '$label_full' WHERE id = '$id'");
        echo "<script>alert('Fiat Details Updated Successfully!');</script>";
        echo "<script>location.replace('admin.php?page=crypto-management');</script>";
    }
    if (isset($_GET['deleteFiatInfo'])) {
        $del_id = $_GET['deleteFiatInfo'];
        $wpdb->query("DELETE FROM $fiats_db WHERE id = '$del_id'");
        echo "<script>alert('Fiat Details Deleted Successfully!');</script>";
        echo "<script>location.replace('admin.php?page=crypto-management');</script>";
    }
    ?>

    <div class="custom_fiat_form">
        <h2 style="margin-bottom: 16px; font-weight: 600;">Cryptocurrency Fiat Form</h2>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th width="25%">FIAT NAME</th>
                    <th width="60%">FIAT FULL NAME</th>
                    <th width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <form action="" method="post">
                    <tr>
                        <td width="25%"><input type="text" id="newFiatLabel" name="newFiatLabel" style="width: 100%; border-radius: 0px;" required></td>
                        <td width="60%"><input type="text" id="newFiatFull" name="newFiatFull" style="width: 100%; border-radius: 0px;" required></td>
                        <td width="15%"><button class="btn btn-primary btn-sm" id="newFiatSubmit" name="newFiatSubmit" type="submit">INSERT</button></td>
                    </tr>
                </form>
            </tbody>
        </table>
        <br>
    </div>

    <?php
    echo "<div class='customCrypto'>
        <h2 class='title'>Cryptocurrency Fiats Information</h2>
        <table class='fiats-table table table-striped table-bordered' id='crypto-fiats-table' style='width: 100%;'>
            <thead>
                <tr>
                    <th width='9%'>ID</th>
                    <th width='15%'>Fiat Name</th>
                    <th width='25%'>Fiat Full Name</th>
                    <th width='11%'>Active</th>
                    <th width='15%'>Action</th>
                    <th width='25%'>Operations</th>
                </tr>
            </thead>
            <tbody>";
                foreach($result as $value) {
                    $id = $value->id;
                    $fiats_name = $value->fiats_name;
                    $fiats_full = $value->fiats_full;
                    $active = $value->active;

                    echo "<tr>
                        <td width='9%'>$id</td>
                        <td width='15%'>$fiats_name</td>
                        <td width='25%'>$fiats_full</td>";
                        if ($active == '1') {
                            echo "<td width='11%'>Yes</td>";
                        }
                        else {
                            echo "<td width='11%'>No</td>";
                        }
                        echo "<td width='15%'><form action='' method='post' id='activeDeactiveDetails' name='activeDeactiveDetails'>
                        <input type='hidden' name='id' id='id' value='$id'>";
                        if ($active == '1') {
                            echo "<input type='submit' name='deactiveDetailsbutton' id='deactiveDetailsbutton' title='Desactive' value='Desactive' class='btn btn-sm btn-success' /></form></td>";
                        }
                        else {
                            echo "<input type='submit' name='activeDetailsbutton' id='activeDetailsbutton' title='Active' value='Active' class='btn btn-sm btn-primary' /></form></td>";
                        }
                        echo "<td width='25%'><a href='admin.php?page=crypto-management&updateFiatInfo=$id'><button class='btn btn-warning btn-sm' type='button'>UPDATE</button></a> <a href='admin.php?page=crypto-management&deleteFiatInfo=$id'><button class='btn btn-danger btn-sm' type='button'>DELETE</button></a></td>
                    </tr>";
                }
            echo "</tbody>
        </table>
    </div>";

    if (isset($_POST['deactiveDetailsbutton'])) {
        $id = $_POST['id'];
        global $wpdb;
        $prefix = $wpdb->prefix;
        $db = $prefix.'custom_crypto_fiats_details';
        $sql = $wpdb->query($wpdb->prepare("UPDATE $db SET active = '0' WHERE id = $id"));

        echo "<script>location.reload();</script>";
    }

    if (isset($_POST['activeDetailsbutton'])) {
        $id = $_POST['id'];
        global $wpdb;
        $prefix = $wpdb->prefix;
        $db = $prefix.'custom_crypto_fiats_details';
        $sql = $wpdb->query($wpdb->prepare("UPDATE $db SET active = '1' WHERE id = $id"));

        echo "<script>location.reload();</script>";
    }

    if (isset($_GET['updateFiatInfo'])) {
        $upt_id = $_GET['updateFiatInfo'];
        $result = $wpdb->get_results("SELECT * FROM $fiats_db WHERE id = '$upt_id'");
        foreach($result as $print) {
            $label = $print->fiats_name;
            $label_full = $print->fiats_full;
        }
        ?>

        <br>
        <br>
        <div class="custom_fiat_form">
            <h2 style="margin-bottom: 16px; font-weight: 600;">Update Cryptocurrency Fiat Details</h2>
            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <th width="25%">FIAT NAME</th>
                        <th width="50%">FIAT FULL NAME</th>
                        <th width="25%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="" method="post">
                        <tr>
                            <td width="25%"><input type="text" id="updateFiatInfoLabel" name="updateFiatInfoLabel" value="<?php echo $label; ?>" style="width: 100%; border-radius: 0px;" required></td>
                            <td width="50%"><input type="text" id="updateFiatInfoFull" name="updateFiatInfoFull" value="<?php echo $label_full; ?>" style="width: 100%; border-radius: 0px;" required></td>
                            <td width="25%"><button class="btn btn-success btn-sm" id="updateFiatInfoSubmit" name="updateFiatInfoSubmit" type="submit">UPDATE</button> <a href="admin.php?page=crypto-management"><button class="btn btn-danger btn-sm" type="button">CANCEL</button></a></td>
                        </tr>
                    </form>
                </tbody>
            </table>
        </div>
    <?php }
}

/* Crypto Management Commissions */
function cryptoCommissionsFormFunction() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $commissions_db = $prefix.'custom_crypto_commissions';
    $result = $wpdb->get_results("SELECT * FROM $commissions_db WHERE `commissions_name` <> ''");
    foreach ($result as $value) {
        if ($value->commissions_name == 'BUY') {
            $getBuy = $value->commissions_percentage;
        }
        if ($value->commissions_name == 'SELL') {
            $getSell = $value->commissions_percentage;
        }
    }

    echo "<div class='customCrypto'>
        <h2 class='title'>Cryptocurrency Commissions Information</h2>
        <table class='fiats-table table table-striped table-bordered' id='crypto-fiats-table' style='width: 100%;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Commissions Name</th>
                    <th>Commissions Percentage</th>
                </tr>
            </thead>
            <tbody>";
                foreach($result as $value) {
                    $id = $value->id;
                    $commissions_name = $value->commissions_name;
                    $commissions_percentage = $value->commissions_percentage;

                    echo "<tr>
                        <td>$id</td>
                        <td>$commissions_name</td>
                        <td>$commissions_percentage</td>
                    </tr>";
                }
            echo "</tbody>
        </table>
    </div>";

    echo "<div class='custom-plugin-commissions'>
        <h2>Cryptocurrency Commissions Details Form</h2>
        <div class='contact-div'>
            <form action='' method='post' id='updateCommissions' name='updateCommissions'>
                <div class='form-group' style='margin-bottom: 1rem;'>
                    <label for='updateBuy' style='font-size: 1rem; color: brown; letter-spacing: -0.015rem;'>Comisión de compra</label>
                    <input type='text' class='form-control form-control-sm' id='updateBuy' name='updateBuy' placeholder='Ingresar Comisión de compra' required value='"; if (isset($getBuy)) { echo $getBuy; } echo "' style='width: 100%; border: 1px solid #ccc; background: #FFF; margin: 3px 0 2px; padding: 3px 10px 3px; border-radius: 3px;'>
                </div>
                <div class='form-group' style='margin-bottom: 1rem;'>
                    <label for='updateSell' style='font-size: 1rem; color: brown; letter-spacing: -0.015rem;'>Comisión de venta</label>
                    <input type='text' class='form-control form-control-sm' id='updateSell' name='updateSell' placeholder='Ingresar Comisión de venta' required value='"; if (isset($getSell)) { echo $getSell; } echo "' style='width: 100%; border: 1px solid #ccc; background: #FFF; margin: 3px 0 2px; padding: 3px 10px 3px; border-radius: 3px;'>
                </div>";
                if (isset($getBuy) && isset($getSell)) {
                    echo '<input type="submit" class="btn btn-success btn-sm" name="updateCommissionsbutton" value="Update Commissions Details" style="margin-top: 5px;">';
                }
                else {
                    echo '<input type="submit" class="btn btn-primary btn-sm" name="insertCommissionsbutton" value="Add Commissions Details" style="margin-top: 5px;">'; 
                } echo"
            </form>
        </div>
        <span id='message'></span>
    </div>";

    $getBuy = htmlspecialchars($_POST['updateBuy']);
    $getSell = htmlspecialchars($_POST['updateSell']);

    if (isset($_POST['insertCommissionsbutton'])) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $sql = $wpdb->insert($prefix.'custom_crypto_commissions', array('commissions_name' => 'BUY', 'commissions_percentage' => $getBuy));
        $sql2 = $wpdb->insert($prefix.'custom_crypto_commissions', array('commissions_name' => 'SELL', 'commissions_percentage' => $getSell));

        if (($sql == true) || ($sql2 == true)) {
            echo "<script>location.reload();</script>";
        }
        else {
            echo "<script>location.reload();</script>";
        }
    }

    if (isset($_POST['updateCommissionsbutton'])) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $db = $prefix.'custom_crypto_commissions';
        $sql = $wpdb->query($wpdb->prepare("UPDATE $db SET commissions_percentage = '$getBuy' WHERE commissions_name = 'BUY'"));
        $sql2 = $wpdb->query($wpdb->prepare("UPDATE $db SET commissions_percentage = '$getSell' WHERE commissions_name = 'SELL'"));

        echo "<script>location.reload();</script>";
    }
}

/* Crypto Management Phone */
function cryptoWhatsAppContactFormFunction() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $phone_db = $prefix.'custom_crypto_contact';
    $result = $wpdb->get_results("SELECT * FROM $phone_db WHERE `name` <> ''");
    foreach ($result as $value) {
        if ($value->name == 'PHONE') {
            $getNumber = $value->contact_number;
        }
    }

    echo "<div class='customCrypto'>
        <h2 class='title'>Cryptocurrency WhatsApp Contact Information</h2>
        <table class='fiats-table table table-striped table-bordered' id='crypto-fiats-table' style='width: 100%;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>WhatsApp Number</th>
                </tr>
            </thead>
            <tbody>";
                foreach($result as $value) {
                    $id = $value->id;
                    $contact_number = $value->contact_number;

                    echo "<tr>
                        <td>$id</td>
                        <td>$contact_number</td>
                    </tr>";
                }
            echo "</tbody>
        </table>
    </div>";

    echo "<div class='custom-plugin-commissions'>
        <h2>Cryptocurrency Commissions Details Form</h2>
        <div class='contact-div'>
            <form action='' method='post' id='updatePhone' name='updatePhone'>
                <div class='form-group' style='margin-bottom: 1rem;'>
                    <label for='updateContact' style='font-size: 1rem; color: brown; letter-spacing: -0.015rem;'>WhatsApp Contact</label>
                    <input type='text' class='form-control form-control-sm' id='updateContact' name='updateContact' placeholder='Enter WhatsApp Contact Number' required value='"; if (isset($getNumber)) { echo $getNumber; } echo "' style='width: 100%; border: 1px solid #ccc; background: #FFF; margin: 3px 0 2px; padding: 3px 10px 3px; border-radius: 3px;'>
                </div>";
                if (isset($getNumber)) {
                    echo '<input type="submit" class="btn btn-success btn-sm" name="updateContactbutton" value="Update WhatsApp Contact Details" style="margin-top: 5px;">';
                }
                else {
                    echo '<input type="submit" class="btn btn-primary btn-sm" name="insertContactbutton" value="Add WhatsApp Contact Details" style="margin-top: 5px;">'; 
                } echo"
            </form>
        </div>
        <span id='message'></span>
    </div>";

    $getNumber = htmlspecialchars($_POST['updateContact']);

    if (isset($_POST['insertContactbutton'])) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $sql = $wpdb->insert($prefix.'custom_crypto_contact', array('name' => 'PHONE', 'contact_number' => $getNumber));

        if ($sql == true) {
            echo "<script>location.reload();</script>";
        }
        else {
            echo "<script>location.reload();</script>";
        }
    }

    if (isset($_POST['updateContactbutton'])) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $db = $prefix.'custom_crypto_contact';
        $sql = $wpdb->query($wpdb->prepare("UPDATE $db SET contact_number = '$getNumber' WHERE name = 'PHONE'"));

        echo "<script>location.reload();</script>";
    }
}

// Register Shortcode
add_shortcode('CCC_Display_Shortcode', 'CCC_shortcode');
// function that runs when Shortcode is called
function CCC_shortcode() {
    ob_start();
    global $wpdb; 
    $prefix = $wpdb->prefix;
    $commissions_db = $prefix.'custom_crypto_commissions';
    $result = $wpdb->get_results("SELECT * FROM $commissions_db WHERE `commissions_name` <> ''");
    foreach ($result as $value) {
        if ($value->commissions_name == 'BUY') {
            $getBuy = $value->commissions_percentage;
        }
        if ($value->commissions_name == 'SELL') {
            $getSell = $value->commissions_percentage;
        }
    }

    $contact_db = $prefix.'custom_crypto_contact';
    $result2 = $wpdb->get_results("SELECT * FROM $contact_db WHERE `contact_number` <> ''");
    foreach ($result2 as $value2) {
        if ($value2->name == 'PHONE') {
            $getContactNumber = $value2->contact_number;
        }
    }

    $fiats_db = $prefix.'custom_crypto_fiats_details';
    $fiats_result = $wpdb->get_results("SELECT * FROM $fiats_db WHERE `active` = '1'"); ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            var get_crypto_value = jQuery("#fiat_currency_select_type :selected").val();

            jQuery(".crypto-price-displaying .crypto-currency").html('Please Wait...');
            jQuery(".crypto-price-displaying .crypto-currency-calculate").html('Please Wait...');

            // Only Numbers
            jQuery("#crypto-currency-input").keypress(function(e) {
                var charCode = (e.which) ? e.which : event.keyCode;
                if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
                    return false;
                }
            });

            // Only Numbers
            jQuery("#fiat-currency-input").keypress(function(e) {
                var charCode = (e.which) ? e.which : event.keyCode;
                if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
                    return false;
                }
            });

            // Clear Inputs
            jQuery("#buy_sell_select_type").change(function(e) {
                jQuery("#crypto-currency-input").val("");
                jQuery("#fiat-currency-input").val("");
            });

            // Clear Inputs & Notification Prices Change
            jQuery("#fiat_currency_select_type").change(function(e) {
                jQuery("#crypto-currency-input").val("");
                jQuery("#fiat-currency-input").val("");

                jQuery(".crypto-price-displaying .crypto-currency").val('Please Wait...');
                jQuery(".crypto-price-displaying .crypto-currency-calculate").val('Please Wait...');
            });

            var get_buy_sell_details = jQuery("#buy_sell_select_type :selected").val();

            /* Displaying Currency on Sliders & Below Notification */
            <?php foreach ($fiats_result as $fiats_result_value) {
                $fsym_name = $fiats_result_value->fiats_name; ?>

                var fsym = '<?php echo $fsym_name; ?>';

                jQuery.get("https://min-api.cryptocompare.com/data/price?fsym=<?php echo $fsym_name; ?>&tsyms=EUR", function(data) {
                    <?php if ($fsym_name == 'BTC' || $fsym_name == 'ETH') { ?>
                        fsym = (data["EUR"] / 1000).toFixed(5);
                        fsym = fsym.replace(/(\d{3})/g, '$1,');
                    <?php }
                    elseif ($fsym_name == 'TRX') { ?>
                        fsym = '0,0'+(data["EUR"] * 100).toFixed(0);
                    <?php }
                    elseif ($fsym_name == 'XRP') { ?>
                        fsym = data["EUR"];
                        fsym = fsym.toFixed(2).replace('.',',');
                    <?php }
                    elseif ($fsym_name == 'EOS' || $fsym_name == 'ADA') { ?>
                        fsym = data["EUR"] * 100;
                        fsym = fsym.toFixed(0).substring(0, 1) + "," + fsym.toString().substring(1, 3);
                    <?php }
                    elseif ($fsym_name == 'BCH' || $fsym_name == 'XMR' || $fsym_name == 'DASH' || $fsym_name == 'ZEC' || $fsym_name == 'LTC' || $fsym_name == 'USDT' || $fsym_name == 'DOGE') { ?>
                        fsym = data["EUR"] * 100;
                        fsym = fsym.toFixed(0).substring(0, 3) + "," + fsym.toString().substring(3, 5);
                    <?php }
                    else { ?>
                        fsym = data["EUR"] * 100;
                        fsym = fsym.toFixed(0).substring(0, 2) + "," + fsym.toString().substring(2, 4);
                    <?php } ?>

                    <!-- Owl Carousel Value -->
                    jQuery('.<?php echo $fsym_name; ?> h3').html(fsym+'€ ⚡');

                    <!-- Default Value on Select -->
                    jQuery('.crypto-price-displaying .crypto-currency').html(get_crypto_value);
                    var get_default_value = jQuery('.'+get_crypto_value+' h3').html();
                    new_value = get_default_value.split('€')[0].toString().replace('.','').replace(',','.');

                    if (get_buy_sell_details == 'BUY') {
                        new_amount = (new_value*<?php echo $getBuy; ?>/100);
                        new_amount = parseFloat(new_amount) + parseFloat(new_value);
                    }
                    if (get_buy_sell_details == 'SELL') {
                        new_amount = (new_value*<?php echo $getSell; ?>/100);
                        new_amount = parseFloat(new_amount) + parseFloat(new_value);
                    }

                    if (get_crypto_value == 'BTC' || get_crypto_value == 'ETH') {
                        new_amount = (new_amount / 1000).toFixed(5);
                        new_amount = new_amount.replace(/(\d{3})/g, '$1,');
                    }
                    else if (get_crypto_value == 'TRX') {
                        new_amount = '0,0'+(new_amount * 100).toFixed(0);
                    }
                    else if (get_crypto_value == 'XRP') {
                        new_amount = new_amount.toFixed(2).replace('.',',');
                    }
                    else if (get_crypto_value == 'EOS' || get_crypto_value == 'ADA') {
                        new_amount = new_amount * 100;
                        new_amount = new_amount.toFixed(0).substring(0, 1) + "," + new_amount.toString().substring(1, 3);
                    }
                    else if (get_crypto_value == 'USDT') {
                        new_amount = new_amount.toFixed(0).substring(0, 1) + "," + new_amount.toString().substring(1, 3);
                    }
                    else {
                        new_amount = new_amount * 100;
                        new_amount = new_amount.toFixed(0).substring(0, 3) + "," + new_amount.toString().substring(3, 5);
                    }

                    jQuery('.crypto-price-displaying .crypto-currency-calculate').html(new_amount+'€');

                    <!-- Change Value while Change the Select Option -->
                    jQuery("#fiat_currency_select_type").change(function(e) {
                        var get_crypto_value = jQuery("#fiat_currency_select_type :selected").val();
                        jQuery('.crypto-price-displaying .crypto-currency').html(get_crypto_value);
                        var get_new_default_value = jQuery('.'+get_crypto_value+' h3').html();
                        new_updated_value = get_new_default_value.split('€')[0].toString().replace('.','').replace(',','.');

                        if (get_buy_sell_details == 'BUY') {
                            new_updated_amount = new_updated_value*(<?php echo $getBuy; ?>/100);
                            new_updated_amount = parseFloat(new_updated_amount) + parseFloat(new_updated_value);
                        }
                        if (get_buy_sell_details == 'SELL') {
                            new_updated_amount = (new_updated_value*<?php echo $getSell; ?>/100);
                            new_updated_amount = parseFloat(new_updated_amount) + parseFloat(new_updated_value);
                        }

                        if (get_crypto_value == 'BTC' || get_crypto_value == 'ETH') {
                            new_updated_amount = (new_updated_amount / 1000).toFixed(5);
                            new_updated_amount = new_updated_amount.replace(/(\d{3})/g, '$1,');
                        }
                        else if (get_crypto_value == 'TRX') {
                            new_updated_amount = '0,0'+(new_updated_amount * 100).toFixed(0);
                        }
                        else if (get_crypto_value == 'XRP') {
                            new_updated_amount = new_updated_amount.toFixed(2).replace('.',',');
                        }
                        else if (get_crypto_value == 'EOS' || get_crypto_value == 'ADA') {
                            new_updated_amount = new_updated_amount * 100;
                            new_updated_amount = new_updated_amount.toFixed(0).substring(0, 1) + "," + new_updated_amount.toString().substring(1, 3);
                        }
                        else if (get_crypto_value == 'USDT') {
                            new_updated_amount = new_updated_amount.toFixed(0).substring(0, 3) + "," + new_updated_amount.toString().substring(3, 5);
                        }
                        else {
                            new_updated_amount = new_updated_amount * 100;
                            new_updated_amount = new_updated_amount.toFixed(0).substring(0, 3) + "," + new_updated_amount.toString().substring(3, 5);
                        }

                        jQuery('.crypto-price-displaying .crypto-currency-calculate').html(new_updated_amount+'€');
                    });
                });
            <?php } ?>
            
            <!-- Owl Carousel -->
            var owl = jQuery('.owl-carousel');
            owl.owlCarousel({
                loop: false,
                nav: true,
                margin: 10,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },            
                    960:{
                        items:5
                    },
                    1200:{
                        items:6
                    }
                }
            });
            owl.on('mousewheel', '.owl-stage', function (e) {
                if (e.deltaYs > 0) {
                    owl.trigger('next.owl');
                } else {
                    owl.trigger('prev.owl');
                }
                e.preventDefault();
            });
            <!-- End -->

            // EURO to Currency Calculate
            jQuery("#crypto-currency-input").keyup(function(e) {
                <!-- Get Amount -->
                var euro_amount = this.value;

                <!-- Get Currency Type - Sell or Buy -->
                var get_buy_sell = jQuery("#buy_sell_select_type :selected").val();
                var get_buy_sell_text = jQuery("#buy_sell_select_type :selected").text();

                <!-- Get Fiat Value -->
                var get_crypto = jQuery("#fiat_currency_select_type :selected").val();

                var percentage = parseFloat('0.0');
                var contactNumber = <?php echo $getContactNumber; ?>;

                var msgPart1 = "Hola, estoy usando el simulador y estoy interesad@ en ";
                var msgPart2 = " Me gustaría más información.";

                if (jQuery('#crypto-currency-input').val().length > 2) {
                    jQuery('#crypto-currency-input').val(this.value.split(",").join(""));
                    var mCrypto = jQuery('#crypto-currency-input').val();
                    var length = mCrypto.length;
                    var euro_amount = mCrypto.substring(0, (length - 2)) + "," + mCrypto.substring((length - 2), length);
                    jQuery('#crypto-currency-input').val(euro_amount);
                }
                else {
                    jQuery('#crypto-currency-input').val(euro_amount);
                }

                var getValue = jQuery('.crypto-currency-calculate').html().split("€").join("");
                new_amount = euro_amount.toString().replace('.','').replace(',','.')/getValue.toString().replace('.','').replace(',','.');
                new_value = getValue.toString().replace('.','').replace(',','.');
                var rate = new_amount*(percentage/100);
                var amount = new_amount-rate;
                jQuery('#fiat-currency-input').val(amount.toFixed(8));

                <!-- jQuery('#fiat-currency-input').val(new_euro_amount); -->

                jQuery('#btnWhatsApp').attr('href', 'https://wa.me/'+contactNumber+'?text='+msgPart1+get_buy_sell_text+' '+amount.toFixed(8)+' '+get_crypto+' - '+euro_amount+'€ => Precio: '+getValue+'€'+msgPart2);
                jQuery('#btnWhatsApp').attr('target', '_bank');
            });

            // Currency to EURO Calculate
            jQuery("#fiat-currency-input").keyup(function(e) {
                <!-- Get Amount -->
                var euro_amount = this.value.split(",").join("");

                <!-- Get Currency Type - Sell or Buy -->
                var get_buy_sell = jQuery("#buy_sell_select_type :selected").val();
                var get_buy_sell_text = jQuery("#buy_sell_select_type :selected").text();

                <!-- Get Fiat Value -->
                var get_crypto = jQuery("#fiat_currency_select_type :selected").val();

                if (jQuery('#crypto-currency-input').val() != '') {
                    jQuery('#crypto-currency-input').val(euro_amount);
                }

                var percentage = parseFloat('0.0');
                var contactNumber = <?php echo $getContactNumber; ?>;

                var msgPart1 = "Hola, estoy usando el simulador y estoy interesad@ en ";
                var msgPart2 = " Me gustaría más información.";

                var getValue = jQuery('.crypto-currency-calculate').html().split("€").join("");

                new_amount = euro_amount.toString().replace(',','.')*getValue.toString().replace('.','').replace(',','.');
                new_value = getValue.toString().replace('.','').replace(',','.');
                var rate = new_amount*(percentage/100);
                var amount = new_amount+rate;
                jQuery('#crypto-currency-input').val(amount.toFixed(2).replace('.',','));

                updated_value = new_amount-rate;

                if (get_buy_sell == 'BUY') {
                    updated_euro_amount = (updated_value*<?php echo $getBuy; ?>/100);
                    updated_euro_amount = parseFloat(updated_euro_amount) + parseFloat(updated_value);
                }
                if (get_buy_sell == 'SELL') {
                    updated_euro_amount = (updated_value*<?php echo $getSell; ?>/100);
                    updated_euro_amount = parseFloat(updated_euro_amount) + parseFloat(updated_value);
                }

                jQuery('#btnWhatsApp').attr('href', 'https://wa.me/'+contactNumber+'?text='+msgPart1+get_buy_sell_text+' '+euro_amount.toString().replace(',','.')+' '+get_crypto+' - '+updated_euro_amount.toFixed(2).replace('.',',')+'€ => Precio: '+getValue+'€'+msgPart2);
                jQuery('#btnWhatsApp').attr('target', '_bank');

                jQuery('#btnWhatsApp').attr('href', 'https://wa.me/'+contactNumber+'?text='+msgPart1+get_buy_sell_text+' '+euro_amount.toString().replace(',','.')+' '+get_crypto+' - '+fiat_val.toFixed(2).replace('.',',')+'€ => Precio: '+getValue+'€'+msgPart2);
                    jQuery('#btnWhatsApp').attr('target', '_bank');
            });

            // Get the Submit Button Label at Initial Time
            var get_submit_label = jQuery("#buy_sell_select_type :selected").text();
            jQuery('.btnWhatsapp').text(get_submit_label);

            // Change the Submit Button Label
            jQuery("#buy_sell_select_type").change(function() {
                var get_submit_label = jQuery("#buy_sell_select_type :selected").text();
                jQuery('.btnWhatsapp').text(get_submit_label);
            });
        });
    </script>

    <div class='container crypto-section'>
        <div class='row'>
            <div class='col-sm-12 text-center'>
                <h1>COMPRA Y VENDE</h1>
                <h2>BITCOIN Y OTRAS CRIPTOMONEDAS</h2>
                <div class='col-sm-12'><span class='marker_long_center' style='position: relative; background: #f7931a; width: 100%; margin: 0 auto; bottom: -8px; height: 7px; float: left; margin-bottom: 50px;'></span></div>
            </div>
        </div>

        <!-- Crypto Carousel -->
        <div class="owl-carousel owl-theme">
            <?php
            foreach($fiats_result as $fiats_new_result_value) {
                $fsym_name = $fiats_new_result_value->fiats_name;
                $fsym_full_name = $fiats_new_result_value->fiats_full;
                $fsym_full_explode = explode(' ', $fsym_full_name);
                if (isset($fsym_full_explode[1])) {
                    $fsym_full = $fsym_full_explode[0]."_".$fsym_full_explode[1];
                }
                else {
                    $fsym_full = $fsym_full_explode[0];
                }
                $fsym_full = strtolower($fsym_full); ?>

                <div class="item <?php echo $fsym_name; ?>"><img src="<?php echo CCC_URL; ?>/assets/admin/img/<?php echo $fsym_full; ?>.png"><p><?php echo $fsym_full_name; ?></p><h3 class="price"></h3></div>
                <!-- 
                // if ($fsym_name == 'BTC' || $fsym_name == 'ETH' || $fsym_name == 'BCH' || $fsym_name == 'TRX' || $fsym_name == 'XMR' || $fsym_name == 'DASH' || $fsym_name == 'ZEC' || $fsym_name == 'LTC' || $fsym_name == 'EOS' || $fsym_name == 'XRP' || $fsym_name == 'ADA') {
                    
                //} -->
                
            <?php } ?>
        </div>

        <div class="col-sm-12 crypto-rules"><p>*El precio no incluye las comisiones de transacción, que pueden variar entre el 3% y el 8%.</p></div>
        <div class="col-sm-12 crypto-rules"><p>*Aceptamos Bizum, transferencia bancaria y efectivo.</p></div>
        <div class="crypto-calculate-area">
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center">USA EL SIMULADOR</h3>
                </div>
                <div class="col-12 col-md-2 crypto-buy-sell-section">
                    <select class="select_type" id="buy_sell_select_type">
                        <option value="BUY">COMPRAR</option>
                        <option value="SELL">VENDER</option>
                    </select>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-down" class="caret modo" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z"></path></svg>
                </div>
                <div class="col-12 col-md-4 crypto-buy-sell-section left">
                    <input name="crypto-currency-input" id="crypto-currency-input" type="text" class="form-control exchange_crypto_currency_input_class" placeholder="0,00" maxlength="12" required="required">
                    <select class="select_type type1">
                        <option value="EUR">EUR</option>
                    </select>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-down" class="caret" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z"></path></svg>
                </div>
                <div class="col-12 col-md-4 crypto-buy-sell-section left">
                    <input name="fiat-currency-input" id="fiat-currency-input" type="text" class="form-control fiat_currency_input_class" placeholder="1,00000000" maxlength="10" required="required">
                    <select class="select_type type2" id="fiat_currency_select_type">
                        <?php global $wpdb;
                        $prefix = $wpdb->prefix;
                        $fiats_db = $prefix.'custom_crypto_fiats_details';
                        $result = $wpdb->get_results("SELECT * FROM $fiats_db WHERE `active` = '1'");
                        foreach ($result as $value) {
                            $fiats_name = $value->fiats_name;
                            echo "<option value='$fiats_name'>$fiats_name</option>";
                        } ?>
                    </select>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-down" class="caret" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z"></path></svg>
                </div>
                <div class="col-12 col-md-2 crypto-buy-sell-section right">
                    <a id="btnWhatsApp" class="btn btn-outline-success btn-lg btn-block btnWhatsapp"></a>
                </div>
            </div>
            <div class="text-center">
                <div class="crypto-price-displaying">
                    <p>Price: 1 <span class="crypto-currency"></span> = <span class="crypto-currency-calculate"></span></p>
                </div>
                <!-- <span class="crypto-info">* Quotes do not apply brokerage fees that can vary between 6% and 8%. <br> ** We accept cash, card and transfer.</span> -->
          </div>
         </div>
    </div>

    <?php 
    $result = ob_get_clean(); //capture the buffer into $result
    return $result; //return it, instead of echoing
}
?>