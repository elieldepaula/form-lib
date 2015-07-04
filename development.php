<?php

class Development extends MX_Controller {

	function __construct()
	{
		$this->load->library('formlib');
	}

	public function index()
	{

		echo '<link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css"
			    rel="stylesheet" type="text/css">
			    <link href="http://pingendo.github.io/pingendo-bootstrap/themes/default/bootstrap.css"
			    rel="stylesheet" type="text/css">';

		echo '<h2>Formulário gerado dinamicamente.</h2>';

		$form = array(
			'form_title' 		=> 'Meu formulário dinâmico',
			'form_submitaction' => 'http://elieldepaula.com.br/contato',
			'form_cancelaction' => 'http://elieldepaula.com.br',
			'form_submit_label' => 'Enviar o formulário',
			'form_cancel_label' => 'Cancelar o envio'
		);

		$this->formlib->initialize($form);

		$formitens = array(
					array(
						'name' => 'nome',
						'id' => 'nome',
						'label' => 'Nome completo',
						'type' => 'text',
						'required' => true,
						'value' => '',
						'placeholder' => 'Digite seu nome completo...',
						'extras' => '0',
						'error' => 'Este campo não pode ficar em branco.'
					),
					array(
						'name' => 'email',
						'id' => 'email',
						'label' => 'Seu email',
						'type' => 'email',
						'required' => true,
						'value' => '',
						'placeholder' => 'Digite seu email...',
						'extras' => '0'
					),
					array(
						'name' => 'mensagem',
						'id' => 'mensagem',
						'label' => 'Sua mensagem',
						'type' => 'textarea',
						'required' => false,
						'value' => '',
						'rows' => '5',
						'placeholder' => 'Digite sua mensagem...',
						'extras' => '0'
					),
					array(
						'name' => 'anexo',
						'id' => 'anexo',
						'label' => 'Envie um anexo',
						'type' => 'file',
						'required' => false,
						'value' => '',
						'placeholder' => 'Selecione seu arquivo...',
						'extras' => '0'
					),
					array(
						'name' => 'copia',
						'id' => 'copia',
						'label' => 'Enviar cópia para mim',
						'type' => 'checkbox',
						'required' => false,
						'value' => '1',
						'checked' => true,
						'extras' => '0'
					),
					array(
						'name' => 'radio',
						'id' => 'radio_1',
						'label' => 'Rádio 1',
						'type' => 'radio',
						'required' => false,
						'value' => '1',
						'checked' => false,
						'extras' => '0'
					),
					array(
						'name' => 'radio',
						'id' => 'copia_2',
						'label' => 'Radio 2',
						'type' => 'radio',
						'required' => false,
						'value' => '2',
						'checked' => true,
						'extras' => '0'
					),
					array(
						'name' => 'categoria_id',
						'id' => 'categoria_id',
						'label' => 'Selecione o assunto',
						'type' => 'select',
						'required' => false,
						'value' => '2', // Vai selecionar um item.
						'itens' => array(
							'1' => 'Contato simples',
							'2' => 'Teste de envio',
							'3' => 'Proposta de trabalho'
						),
						'extras' => '0'
					)
					// ...
				);

		$this->formlib->set_itens($formitens);

		echo $this->formlib->get_form();

	}

}