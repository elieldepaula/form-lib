<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Example extends CI_Controller {

	public function index()
	{
		
		/**
		* Load the library and the dependencies.
		*/
		$formlib = new \FormLib\FormLib();
		
		/**
		* Prepares an array with the general attributes of the form.
		*/
		$form_options = [
			/**
			* Title of the form.
			*/
			'title' 		=> 'Exemplo de utilização do FormLib.php',
			/**
			* Action of the form.
			*/
			'action' 		=> 'admin/main/login',
			/**
			* Style type of the form, could be form-horizontal or form-vertical
			* according to the bootstrap documentation.
			*/
			# 'type' 			=> 'form-vertical',
			/**
			* Method of the form, could be post, get or multipart in case
			* to use uploads fields.
			*/
			# 'method'		=> 'post',
			/**
			* Wrapp or not the form between header and footer templates.
			* Note: To use wrap_form you must copy the form folter into your views directory.
			*/
			// 'wrap_form' 	=> false,
			/**
			* Setup the header wrapper template. (Default: views/form/template_header).
			*/
			# 'template_header' => 'exemplo',
			/**
			* Setup the footer wrapper template. (Default: views/form/template_header).
			*/
			# 'template_footer' => 'exemplo',
		];

		$options = array(
			'small'         => 'Small Shirt',
			'med'           => 'Medium Shirt',
			'large'         => 'Large Shirt',
			'xlarge'        => 'Extra Large Shirt',
		);

		/**
		* Prepares an array with te itens of the form. Each fild item is basically
		* as the example: 'Label text' => [common attributes used into codeigniter.]
		*
		* See the examples below:
		*/
		$form_fields = [
			'hidden' 		=> ['type'=>'hidden','name'=>'id', 'value'=>'123'],
			'Texto' 		=> ['type'=>'text','name'=>'nome', 'value'=>'Valor padrão', 'error_msg'=>'Mensagem de erro', 'placeholder'=>'Informe um texto...', 'required'=>true],
			'Upload' 		=> ['type'=>'upload','name'=>'foto', 'placeholder'=>'Selecione uma foto...'],
			'Dropdown'		=> ['type'=>'dropdown','name'=>'catg', 'options'=>$options],
			'Multi Select'	=> ['type'=>'multiselect','name'=>'catg', 'options'=>$options],
			'TextArea' 		=> ['type'=>'textarea', 'rows'=>'10', 'name'=>'obs', 'id'=>'campo_texto', 'class'=>'ckeditor', 'placeholder'=>'Informe alguma observação...'],
			'Check-Box'		=> ['type'=>'checkbox', 'name'=>'altera_imagem', 'value'=>'1', 'checked'=>true],
			'Radio-1'		=> ['type'=>'radio', 'name'=>'radio_check', 'value'=>'1', 'checked'=>true],
			'Radio-2'		=> ['type'=>'radio', 'name'=>'radio_check', 'value'=>'2', 'checked'=>false],
			'E-Mail' 		=> ['type'=>'email', 'name'=>'email', 'id'=>'email', 'placeholder'=>'Digite seu email...', 'required'=>true],
			'Senha' 		=> ['type'=>'password', 'name'=>'senha', 'id'=>'senha', 'placeholder'=>'Digite sua senha...', 'required'=>true]
		];

		/**
		* Prepares an array with the buttons attributes of the form.
		*/
		$form_buttons = [
			['content' => 'Enviar o formulário', 'type'=>'submit', 'class' => 'btn btn-primary', 'name'=>'', 'id'=>'', 'value'=>''],
			['content' => 'Limpar o formulário', 'type'=>'reset', 'class' => 'btn btn-warning', 'name'=>'', 'id'=>'', 'value'=>''],
			['content' => 'Cancelar', 'type'=>'anchor', 'url' => site_url(), 'class' => 'btn btn-danger', 'name'=>'', 'id'=>'', 'value'=>'']
		];
		
		/**
		* Send the options, fields and buttons to Formlib.
		*/
		$formlib->set_options($form_options);
		$formlib->set_fields($form_fields);
		$formlib->set_buttons($form_buttons);

		/**
		* Renderize the form.
		*/
		echo $formulario = $formlib->render();

		/**
		* Use it into your view.
		*/
		$this->load->view('your_view', ['content' => $formulario]);

	}

}