<?php
class Team{
    public function __construct(){
        add_submenu_page("football-league", "Team", "Team", 'manage_options', "team", array($this,"Teamadminpage"));
    }
    public function Teamadminpage(){
        global $wpdb;
        if($_REQUEST['action'] == 'delete'){
            $table = 'wp_team';
            $wpdb->query("DELETE FROM $table WHERE id = ".$_REQUEST['id'] );
        }
        $err = array();
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
            $table = 'wp_team';
            if(count($err) == 0){
                $data = array(
                    'name' => $_POST['name'],
                    'league_id' => $_POST['league_id'],
                    'logo' => $imageurl,
                    'history' => $_POST['history'], 
                    'nickname' => $_POST['nickname']
                );
                $format = array(
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                );
                $success=$wpdb->insert( $table, $data, $format );
            }else{
                echo $err['logo'];
            }
            
        }
        $resultsleague = $wpdb->get_results("SELECT * FROM wp_league");
        $arrleague = array();
        foreach($resultsleague as $val){
            $arrleague[$val->id] = $val->name;
        }
        ?>
        <div id="col-container" >
            <div id="col-left">
                <h2>Add Team</h2>
                <form method="post" class="validate" enctype='multipart/form-data'>
                    <div class="form-field form-required term-name-wrap">
                        <label>Add Name</label>
                        <input type="text" name="name" required>
                    </div>    
                    <div class="form-field form-required term-name-wrap">
                        <label>Select League</label>
                        <select name="league_id">
                            <?php
                            foreach($resultsleague as $resultleague ){
                            ?>
                            <option value="<?php echo $resultleague->id; ?>"><?php echo $resultleague->name; ?></option>
                            <?php    
                            }
                            ?>
                        </select>
                    </div>  
                    <div class="form-field form-required term-name-wrap">
                        <label>Add Logo</label>
                        <input type="file" name="logo" required><br />
                    </div>    
                    <div class="form-field form-required term-name-wrap">
                        <label>Add history</label>
                        <textarea name="history" required></textarea><br />
                    </div>    
                    <div class="form-field form-required term-name-wrap">
                        <label>Add Nickname</label>
                        <input type="text" name="nickname" required><br />
                    </div>
                    <input type="submit" class="button button-primary" value="Save" name="save">
                </form>
            </div>
            <div id="col-right">
                <table class="wp-list-table widefat fixed striped table-view-list pages">
                    <tr>
                        <th>Teams</th>
                        <th>League</th>
                        <th>Logo</th>
                        <th>History</th>
                        <th>Nickname</th>
                        <th>Operation</th>
                    </tr>
                    
                <?php
                
                    $results = $wpdb->get_results("SELECT * FROM wp_team");
                    
                    foreach($results as $key=>$result){
                        echo "<tr>";
                        echo "<td>".$result->name."</td>";
                        echo "<td>".$arrleague[$result->league_id]."</td>";
                        echo "<td><img src='".$result->logo."'></td>";
                        echo "<td>".$result->history."</td>";
                        echo "<td>".$result->nickname."</td>";
                        echo "<td><a href='?page=team&id=$result->id&action=delete' class='submitdelete'>Trash</a></td>";
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