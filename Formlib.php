<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FormLib
 *
 * An open source library to renderize complex forms using CodeIgniter and PHP.
 *
 *   Dependency: 
 * ----------------------------------------------------------------------------
 * - Bootstrap (http://getbootstrap.com/)
 * - Form Helper
 * - URL Helper 
 *
 *
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     Library
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @copyright   Copyright (c) 2015 - 2016, Eliel de Paula. (http://elieldepaula.com.br/)
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://github.com/elieldepaula/FormLib
 * @version     1.0.0
 */
class Formlib 
{

	/**
	* HTML output.
    *
    * @var string
	*/
	protected $output_html;

	/**
    * Template for the top of the form wrapper.
    *
    * @var string
    */
	protected $template_header = 'forms/template_header';

    /**
    * Template for the footer of the form wrapper.
    *
    * @var string
    */
	protected $template_footer = 'forms/template_footer';

    /**
    * Title of the form used into the form wrapper.
    *
    * @var string
    */
	protected $form_title;

    /**
    * The bootstrap style class for the form, available options:
    * form-horizontal (default with 2 collumns)
    * form-vertical
    *
    * See more details on http://getbootstrap.com/css/#forms
    *
    * @var string
    */
	protected $form_type = 'form-horizontal';

    /**
    * URL action of the form.
    *
    * @var string
    */
	protected $form_action;

    /**
    * Method of the form, available options:
    *
    * post      - Generates a method="post" form.
    * get       - Generates a method="get" form.
    * multipart - Generates a enctype="multipart/form-data" form.
    *
    * @var string
    */
	protected $form_method = 'post';

    /**
    * Define if wrapp the form or not.
    *
    * @var boolean
    */
	protected $wrap_form = TRUE;

    /**
    * Array data for send content to header template.
    *
    * @var array
    */
	protected $data_header;

    /**
    * Array data for send content to footer template.
    *
    * @var array
    */
	protected $data_footer;

	/**
    * Array with the fields values.
    *
    * @var array
    */
	protected $form_fields;

	/**
    * Array with the buttons values.
    *
    * @var array
    */
	protected $form_buttons;

    /**
    * Contructor of the library.
    *
    * @return void
    */
	public function __construct()
    {
        log_message('debug', "Formlib Class Initialized");
    }

    /**
    * Get the instance of CodeIgniter into the library.
    *
    * @param $var
    * @return mixed
    */
    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
    * Set the options array.
    *
    * @param $options array - Array of options to the form.
    * @return void
    */
    private function do_set_options($options)
    {
        if(isset($options['template_header']))
            $this->template_header = $options['template_header'];

        if(isset($options['template_footer']))
            $this->template_footer = $options['template_footer'];

        if(isset($options['type']))
            $this->form_type = $options['type'];

        if(isset($options['action']))
            $this->form_action = $options['action'];

        if(isset($options['method']))
            $this->form_method = $options['method'];

        if(isset($options['title']))
            $this->form_title = $options['title'];

        if(isset($options['wrap_form']))
            $this->wrap_form = $options['wrap_form'];
    }

    /**
    * Renderize the form with the especifications sender by the user.
    *
    * @return string
    */
    private function do_renderize_form()
    {
    	if(isset($this->form_title))
    		$this->data_header['title'] = $this->form_title;
    	if($this->wrap_form)
    		$this->output_html .= $this->load->view($this->template_header, $this->data_header, true);
    	$this->output_html .= $this->do_renderize_form_tag();
    	$this->output_html .= $this->do_render_fields();
    	$this->output_html .= "<hr/>\n";
    	$this->output_html .= $this->do_render_buttons();
    	$this->output_html .= form_close();
    	if($this->wrap_form)
    		$this->output_html .= $this->load->view($this->template_footer, $this->data_footer, true);
        log_message('debug', "Form ".$this->form_title." was correctly rendered.");
    	return $this->output_html;
    }

    /**
    * Renderize the <form> tag as the $form_type.
    *
    * @return String
    */
    private function do_renderize_form_tag()
    {
		$attr = [];
		$attr['role'] = 'form';
		if(isset($this->form_type))
    		$attr['class'] = $this->form_type;
		switch ($this->form_method) {
			case 'get':
				$attr['method'] = 'get';
				$this->output_html .= "\n".form_open($this->form_action, $attr);
				break;
			case 'multipart':
				$this->output_html .= "\n".form_open_multipart($this->form_action, $attr);
				break;
			default:
				$this->output_html .= "\n".form_open($this->form_action, $attr);
				break;
		}
    }

    /**
    * Renderize the form field according to the type.
    *
    * @return void
    */
    private function do_render_fields()
    {
    	foreach ($this->form_fields as $key => $value) {

    		if(!isset($value['type'])){
                show_error("<b>FormLib says:</b> I can not renderize a field without the TYPE value.");
                log_message('error', "Failed rendering the form field without type.");
            }

    		if(!isset($value['class']))$value['class'] = 'form-control'; else $value['class'] = $value['class'].' form-control';
    		if(!isset($value['name']))$value['name'] = ' ';
            if(!isset($value['error_msg']))$value['error_msg'] = '';
    		if(!isset($value['id']))$value['id'] = $value['name'];

    		switch ($value['type']) {
    			case 'textarea':
    				$this->output_html .= $this->field_wrapper($value['id'], $key, form_textarea($value), $value['error_msg']);
    				break;
    			case 'email':
    				$this->output_html .= $this->field_wrapper($value['id'], $key, form_input($value), $value['error_msg']);
    				break;
    			case 'upload':
    				$this->output_html .= $this->field_wrapper($value['id'], $key, form_upload($value), $value['error_msg']);
    				break;
    			case 'password':
    				$this->output_html .= $this->field_wrapper($value['id'], $key, form_password($value), $value['error_msg']);
    				break;
    			case 'dropdown':
    				if(!isset($value['selected']))$value['selected'] = null;
    				$this->output_html .= $this->field_wrapper($value['id'], $key, form_dropdown($value['name'], $value['options'], $value['selected'], ['class'=>$value['class']]), $value['error_msg']);
    				break;
    			case 'multiselect':
    				if(!isset($value['selected']))$value['selected'] = null;
    				$this->output_html .= $this->field_wrapper($value['id'], $key, form_multiselect($value['name'], $value['options'], $value['selected'], ['class'=>$value['class']]), $value['error_msg']);
    				break;
                case 'checkbox':
                    $this->output_html .= $this->checkbox_wrapper($key, form_checkbox($value['name'], $value['value'], $value['checked']));
                    break;
                case 'radio':
                    $this->output_html .= $this->radio_wrapper($key, form_radio($value['name'], $value['value'], $value['checked']));
                    break;
    			case 'hidden':
    				$this->output_html .= form_hidden($value['name'], $value['value']);
    				break;
    			default:
    				$this->output_html .= $this->field_wrapper($value['id'], $key, form_input($value), $value['error_msg']);
    				break;
    		}
    	}
    }

    /**
    * Prepare some error message to show with the bootstrap code.
    *
    * @var string - Error message
    * @return string
    */
    private function do_show_error($error_msg)
    {
        if(isset($error_msg))
            return "<p class=\"label label-danger\">".$error_msg."</p>\n";
    }

    /**
    * Renderize the form buttons. Available types:
    * submit - Submit the form.
    * reset  - Reset the form data.
    * link   - Creates a link with anchor(), you can use to "cancel" and send 
    *          the user to another page for example.
    *
    * @return string
    */
    private function do_render_buttons()
    {
    	$button_html = "";
    	foreach($this->form_buttons as $value) {
    		if($value['type'] == 'link')
    			$button_html .= anchor($value['url'], $value['content'], ['class' => $value['class']]);
    		else
    			$button_html .= form_button($value);
    	}
    	return $this->button_wrapper($button_html);
    }

    /**
    * Wrapp the form field according to the form type using 1 or 2 collumns.
    *
    * @param $id string - Field id.
    * @param $label string - Field label.
    * @param $field string - Rendered field.
    * @param $error_msg string - Error message.
    * @return void
    */
    private function field_wrapper($id, $label, $field, $error_msg = '')
    {
    	if($this->form_type == 'form-horizontal'){
    		# 2 collumns
			$this->output_html .= "<div class=\"form-group\">\n";
			$this->output_html .= "<label for=\"".$id."\" class=\"col-sm-2 control-label\">".$label."</label>\n";
			$this->output_html .= "<div class=\"col-sm-10\">\n";
			$this->output_html .= "$field\n";
            $this->output_html .= $this->do_show_error($error_msg)."\n";
			$this->output_html .= "</div>\n";
			$this->output_html .= "</div>\n";
    	} else {
    		# 1 collumn
			$this->output_html .= "<div class=\"form-group\">\n";
			$this->output_html .= "<label for=\"".$id."\">".$label."</label>\n";
			$this->output_html .= "$field\n";
            $this->output_html .= $this->do_show_error($error_msg)."\n";
			$this->output_html .= "</div>\n";
    	}
    }

    /**
    * Wrapp the form buttons according to the form type using 1 or 2 collumns.
    *
    * @param $buttons string - Rendered buttons.
    * @return void
    */
    private function button_wrapper($buttons)
    {
    	if($this->form_type == 'form-horizontal'){
    		# 2 collumns
			$this->output_html .= "<div class=\"form-group\">\n";
			$this->output_html .= "<div class=\"col-sm-offset-2 col-sm-10\">\n";
    		$this->output_html .= "$buttons\n";
    		$this->output_html .= "</div>\n";
    		$this->output_html .= "</div>\n";
    	} else {
    		# 1 collumn
			$this->output_html .= "$buttons\n";
    	}
    }

    /**
    * Wrapp the checkbox field according to the form type using 1 or 2 collumns.
    *
    * @param $label string - Field label.
    * @param $checkbox string - Rendered field.
    * @param $error_msg string - Error message.
    * @return void
    */
    private function checkbox_wrapper($label, $checkbox, $error_msg = '')
    {
        if($this->form_type == 'form-horizontal'){
            # 2 collumns
            $this->output_html .= "<div class=\"form-group\">\n";
            $this->output_html .= "<div class=\"col-sm-offset-2 col-sm-10\">\n";
            $this->output_html .= "<div class=\"checkbox\">\n";
            $this->output_html .= "<label>$checkbox ".$label."</label>\n";
            $this->output_html .= $this->do_show_error($error_msg)."\n";
            $this->output_html .= "</div>\n";
            $this->output_html .= "</div>\n";
            $this->output_html .= "</div>\n";
        } else {
            # 1 collumn
            $this->output_html .= "<div class=\"checkbox\">\n";
            $this->output_html .= "<label>$checkbox ".$label."</label>\n";
            $this->output_html .= $this->do_show_error($error_msg)."\n";
            $this->output_html .= "</div>\n";
        }
    }

    /**
    * Wrapp the radio field according to the form type using 1 or 2 collumns.
    *
    * @param $label string - Field label.
    * @param $radio string - Rendered field.
    * @param $error_msg string - Error message.
    * @return void
    */
    private function radio_wrapper($label, $radio, $error_msg = '')
    {
        if($this->form_type == 'form-horizontal'){
            # 2 collumns
            $this->output_html .= "<div class=\"form-group\">\n";
            $this->output_html .= "<div class=\"col-sm-offset-2 col-sm-10\">\n";
            $this->output_html .= "<div class=\"radio\">\n";
            $this->output_html .= "<label>$radio ".$label."</label>\n";
            $this->output_html .= $this->do_show_error($error_msg)."\n";
            $this->output_html .= "</div>\n";
            $this->output_html .= "</div>\n";
            $this->output_html .= "</div>\n";
        } else {
            # 1 collumn
            $this->output_html .= "<div class=\"radio\">\n";
            $this->output_html .= "<label>$radio ".$label."</label>\n";
            $this->output_html .= $this->do_show_error($error_msg)."\n";
            $this->output_html .= "</div>\n";
        }
    }

    /*--------------------------------------------------------------------------
    * From here you can found the methods of the public API, according to
    * the semantic pattern of versioning.
    --------------------------------------------------------------------------*/

    /**
    * Set the options array.
    *
    * Learn more: https://github.com/elieldepaula/FormLib
    *
    * @param $options array - Array of options to the form.
    * @return void
    */
    public function set_options($options = [])
    {
    	$this->do_set_options($options);
    }

    /**
    * Set the fields array.
    *
    * Learn more: https://github.com/elieldepaula/FormLib
    *
    * @param $fields array - Array of fields to the form.
    * @return void
    */
    public function set_fields($fields = [])
    {
    	$this->form_fields = $fields;
    }

    /**
    * Set the buttons array.
    *
    * Learn more: https://github.com/elieldepaula/FormLib
    *
    * @param $buttons array - Array of buttons to the form.
    * @return void
    */
    public function set_buttons($buttons = [])
    {
    	$this->form_buttons = $buttons;
    }

    /**
    * Renderize the form according to the options, fields and buttons.
    *
    * Learn more: https://github.com/elieldepaula/FormLib
    *
    * @return string - HTML code of the form.
    */
    public function render()
    {
    	return $this->do_renderize_form();
    }

}