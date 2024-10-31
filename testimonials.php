<?php

/*







Plugin Name: Random Testimonials     







Plugin URI: http://www.bluelayermedia.com    







Description: Display random testimonials on your website.  







Author: BlueLayerMedia   







Version: 1.3    







Author URI: http://www.bluelayermedia.com   







*/


register_activation_hook(__file__, 'RandomTestimonials_install');


function RandomTestimonials_install()
{


    global $wpdb;


    $table_name = $wpdb->prefix . "testimonials";


    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
    {


        $sql = "CREATE TABLE " . $table_name . " (  







      id int(10) NOT NULL AUTO_INCREMENT,   







      name varchar(255) NOT NULL,  







      quote text NOT NULL,   







      queued tinyint NOT NULL,







      PRIMARY KEY (id),  







      KEY id (id)  







    );";


        mysql_query($sql);


    }


}


add_action('admin_menu', 'RandomTestimonials_admin_actions');


function RandomTestimonials_admin_actions()
{


    //add_options_page('Random Testimonials', 'Random Testimonials', 'manage_options', 'Random Testimonials', 'RandomTestimonials_admin');


    add_submenu_page('options-general.php', __('Random Testimonials'), __('Random Testimonials'),
        'manage_options', 'RandomTestimonials_admin', 'RandomTestimonials_admin');


}


function RandomTestimonials_admin()
{


    include ('adminpanel.php');


}


function sc_liste($atts)
{


    extract(shortcode_atts(array('limit' => '5', ), $atts));


    global $wpdb;


    $user_count = $wpdb->get_results("SELECT * FROM wp_testimonials WHERE queued=0 ORDER BY RAND() LIMIT $limit");


    foreach ($user_count as $output)
    {


        $htmlOutput .= "<div>";


        $htmlOutput .= "<em>" . $output->quote . "</em><br />";


        $htmlOutput .= "<strong> - " . $output->name . "</strong>";


        $htmlOutput .= "</div>";


    }


    return $htmlOutput;


}


add_shortcode("show_testimonials", "sc_liste");


function testimonial_form()
{


    return '



<html>



    <body>    



        <fieldset>



            <LEGEND><strong>Submit Your Testimonial:</strong></LEGEND> 



                <form action="' . WP_PLUGIN_URL . '/random-testimonials/submittestimonial.php" method="post">



                    <p style="padding-left:10pt;">Quote: <textarea name="quote" rows="3" cols="25"></textarea></p> 



                    <p style="padding-left:13pt;">Name:  <input type="text" name="name" size="33" /></p>         



                    <p style="padding-left:41pt;"><input type="submit" name="addQuote" value="Submit" align="center"></p>   



                </form>    



        </fieldset>



    </body>



</html> ';


}


add_action("widgets_init", array('Random_Testimonials', 'register'));


class Random_Testimonials
{
    function control()
    {


        $limitdata = get_option('random_testimonials_limit','3');

?>



      <p><label>Number of Testimonials to Display: <input name="random_testimonials_limit"  type="text" value="<?php

        echo $limitdata ;

?>" /></label></p>  



        <?php

        if (isset($_POST['random_testimonials_limit']))
        {


            $limitdata = attribute_escape($_POST['random_testimonials_limit']);


            update_option('random_testimonials_limit',  $limitdata);
        }
    }


    function widget($args)
    {
        echo $args['before_widget'];


        echo $args['before_title'] . 'Random Testimonials' . $args['after_title'];


        global $wpdb;

        $limitdata = get_option('random_testimonials_limit','3');        

        $user_count = $wpdb->get_results("SELECT * FROM wp_testimonials WHERE queued=0 ORDER BY RAND() LIMIT $limitdata");

        foreach ($user_count as $output)
        {


            $htmlOutput .= "<div>";


            $htmlOutput .= "<em>" . $output->quote . "</em><br />";


            $htmlOutput .= "<strong> - " . $output->name . "</strong>";


            $htmlOutput .= "</div>";

        }


        echo $htmlOutput;


        echo $args['after_widget'];
    }


    function register()
    {


        register_sidebar_widget('Random Testimonials', array('Random_Testimonials',
            'widget'));


        register_widget_control('Random Testimonials', array('Random_Testimonials',
            'control'));
    }
}


add_shortcode("testimonial_form", "testimonial_form");

?>