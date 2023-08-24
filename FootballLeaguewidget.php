<?php
    use Elementor\Widget_Base;
    use Elementor\Controls_Manager;
    class FootballLeaguewidget extends Widget_Base {
        public function get_name() {
            return 'football league';
        }
        public function get_title() {
            return esc_html__( 'FootballLeague', 'elementor-footballleague-widget' );
        }
        public function get_icon() {
            return 'eicon-code';
        }
        public function get_custom_help_url() {
            return 'https://developers.elementor.com/docs/widgets/';
        }
        public function get_categories() {
            return [ 'general' ];
        }
        public function get_keywords() {
            return [ 'league' ];
        }
        protected function register_controls() {
            global $wpdb;
            $results = $wpdb->get_results("SELECT * FROM wp_league");
            $arrleague = array();
            foreach($results as $key=>$result){
                $arrleague[$result->id] = $result->name; 
            }
            $this->start_controls_section(
                'content_section',
                [
                    'label' => esc_html__( 'League', 'elementor-footballeague-widget' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
    
            $this->add_control(
                'League',
                [
                    'label' => esc_html__( 'League Name', 'elementor-footballleague-widget' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $arrleague,
                    'selectors' => [
                        '{{WRAPPER}} .title' => 'border-style: {{VALUE}};',
                    ],
                ],
                
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .title',
                ]
            );
            $this->add_control(
                'color',
                [
                    'label' => esc_html__( 'Color', 'elementor-footballleague-widget' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-list-widget-text' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .elementor-list-widget-text > a' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->end_controls_section();
    
        }
        protected function render() {
            global $wpdb;
            $settings = $this->get_settings_for_display();
            $results = $wpdb->get_results("SELECT * FROM wp_league where id=".$settings['League']);
            $resultsteam = $wpdb->get_results("SELECT * FROM wp_team where league_id=".$results[0]->id);
            $resultsleague = $wpdb->get_results("SELECT * FROM wp_league");
            $arrleague = array();
            foreach($resultsleague as $val){
                $arrleague[$val->id] = $val->name;
            }
            echo '<div class="title" style="color: ' . esc_attr( $settings['color'] ) . '">'. $results[0]->name.' </div>';
            echo '<table class="wp-list-table widefat fixed striped table-view-list pages">';
            echo '<tr>
                <th>Teams</th>
                <th>League</th>
                <th>Logo</th>
                <th>History</th>
                <th>Nickname</th>
            </tr>';
            foreach($resultsteam as $key=>$result){
                echo "<tr>";
                echo "<td>".$result->name."</td>";
                echo "<td>".$arrleague[$result->league_id]."</td>";
                echo "<td><img src='".$result->logo."'></td>";
                echo "<td>".$result->history."</td>";
                echo "<td>".$result->nickname."</td>";
                echo "</tr>";
            }
            echo '</table>';
        }
        protected function content_template() {
            ?>
            <div>{{{ settings.color }}}</div>
            <?php
        }
    }
?>