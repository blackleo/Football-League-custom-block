<?php
class League{
    public function __construct(){
        add_submenu_page('football-league','League','League', 'manage_options','league',array($this,'Leageadminpage'),'dashicons-tickets', 6 );
    }
    public function Leageadminpage(){
        global $wpdb;
        
        if(array_key_exists('action',$_REQUEST) && $_REQUEST['action'] == 'delete'){
            $table = 'wp_league';
            $wpdb->query("DELETE FROM $table WHERE id = ".$_REQUEST['id'] );
        }
        if (!empty($_POST)) {
            if($_FILES['logo']['name'] != ''){
                $uploadedfile = $_FILES['logo'];
                $upload_overrides = array( 'test_form' => false );
            
                $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                $imageurl = "";
                if ( $movefile && ! isset( $movefile['error'] ) ) {
                   $imageurl = $movefile['url'];
                   
                } else {
                   $err['logo'] = $movefile['error'];
                }
            }
            $table = 'wp_league';
            $data = array(
                'name' => $_POST['name'],
                'logo' => $imageurl
            );
            $format = array(
                '%s',
                '%s'
            );
            $success=$wpdb->insert( $table, $data, $format );
            //wp_safe_redirect(esc_url(site_url( '/wp-admin/?page=league.php' )));
        }
            ?>
            <div id="col-container" >
                <div id="col-left">
                    <h2>Add Leagues</h2>
                    <form method="post" class="validate" enctype='multipart/form-data'>
                        <div class="form-field form-required term-name-wrap">
                            <label>Add League</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="form-field form-required term-name-wrap">
                            <label>Add Logo</label>
                            <input type="file" name="logo" required><br />
                        </div>  
                        <div class="form-field form-required term-name-wrap">    
                            <input type="submit" class="button button-primary" value="Save" name="save">
                        </div>
                    </form>
                </div>
                <div id="col-right">    
                    <table class="wp-list-table widefat fixed striped table-view-list pages">
                        <tr>
                            <th>Leagues</th>
                            <th>Logo</th>
                            <th>Operation</th>
                        </tr>
                        
                        <?php
                        $results = $wpdb->get_results("SELECT * FROM wp_league");
                        foreach($results as $key=>$result){
                            echo "<tr>";
                            echo "<td>".$result->name."</td>";
                            echo "<td><img src='".$result->logo."'></td>";
                            echo "<td><a href='?page=league&id=$result->id&action=delete' class='submitdelete'>Trash</a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>    
        <?php  
    }
}
?>