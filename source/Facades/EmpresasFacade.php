<?php


namespace Source\Facades;

use CoffeeCode\Uploader\File;
use CoffeeCode\Uploader\Image;
use Source\Models\ContatosModel;
use Source\Models\CotacoesModel;
use Source\Models\EmpresasModel;
use Source\Models\Users;
use Source\Support\Whatsapp;

class EmpresasFacade
{

    public function new(EmpresasModel $empresa ,array $data): bool
    {
	

        $empresa->dono_id = $_SESSION['id'];

        if($data['vendedor_id'] !== "") {
            $empresa->vendedor_id = $data['vendedor_id'];
        }


        $empresa->name = strtoupper(str_replace("'", "", $data['empresa_name']));
        if($data['contato-origem'] !== "") {
            $empresa->responsavel = $data['contato-origem'];
        }
        if($data['email-origem'] !== "") {
            $empresa->email = $data['email-origem'];
        }

            $empresa->estado = str_replace("'", "", $data['uf']);
            $empresa->cidade = str_replace("'", "", $data['city']);

        if($data['rua'] !== "") {
            $empresa->rua = $data['rua'];
        }
        if($data['complemento'] !== "") {
            $empresa->complemento = $data['complemento'];
        }
        if($data['cnpj'] !== "") {
            $empresa->cnpj = $data['cnpj'];
        } else {
            $empresa->cnpj = "00000000000000";
        }
        if($data['pagamento'] !== "") {
            $empresa->pagamento = $data['pagamento'];
        } else {
            $empresa->pagamento = "A Combinar";
        }
        if($data['cargo-origem'] !== "") {
            $empresa->contato = $data['cargo-origem'];
        }

        $empresa->importante = $data['importante'];
        $empresa->spc = $data['spc'];
        $empresa->status = $data['status'];

        $result = $empresa->save();
        
     

        if(!$result) {
            return false;
        }
        else{
    
		   $contatoempresa = new ContatosModel();
		   
			if($data['cnpj'])
			{
			    $contatoempresa->cnpj = $data['cnpj'];
			}
		        $contatoempresa->empresa = $data['empresa_name'];
		        $contatoempresa->cnpj = $data['cnpj'];
			$contatoempresa->responsavel = $data['contato-origem'];
			$contatoempresa->cargo = $data['cargo-origem'];
			$contatoempresa->email = $data['email-origem'];
			$contatoempresa->contato = $data['telefone-origem'];
			$contatoempresa->status = "Contato Empresa";
            $contatoempresa->user_name = $_SESSION['name'];
			
                $result1 = $contatoempresa->save();

                //Enviar msg no whatsapp de bem vindo
                (new Whatsapp())->bootstrap($data['telefone-origem'], "*Parabéns você foi cadastrado no sistema da TRANSPETRO. Você receberá cotações, ordem de coleta, posição de cada transpote e entrega também por aqui. Agradecemos a oportunidade de mostrar um pouco do que uma das melhores transportadoras do pais pode fazer por você!*")->queue();

                if(!$result1) {
                    return false;
                }
                else{
                    for ($i=0; $i<10;$i++){
                        if(isset($data['contato-origem_'.$i])){
				
				   $y = $i+1;
				
				   $contatoempresa2 = new ContatosModel();
				   
					if($data['cnpj'])
						{
						    $contatoempresa2->cnpj = $data['cnpj'];
						}
                    $contatoempresa2->empresa = $data['empresa_name'];
                    $contatoempresa2->cnpj = $data['cnpj'];
					$contatoempresa2->responsavel = $data['contato-origem_'.$i];
					$contatoempresa2->cargo = $data['cargo-origem_'.$i];
					$contatoempresa2->email = $data['email-origem_'.$i];
                    
					$contatoempresa2->contato = $data['telefone-origem_'.$i];
                    $contatoempresa2->status = "Contato Empresa";
                    $contatoempresa2->user_name = $_SESSION['name'];
					
				   $result2 = $contatoempresa2->save();
				   
				   
					if(!$result2) {
					    return false;
					}
				   
				   
					   if(!isset($data['contato-origem_'.$y])){
					   	$i = 10;
					   }
				}
                	}
                	return true;
		    }
	    }
    }

    public function delete(array $data): bool
    {
        $empresa = (new EmpresasModel())->find("id = :id", "id={$data['id']}")->fetch();

        $cotacoes = (new CotacoesModel())->find("cliente_id = :id", "id={$data['id']}")->count();

        if($cotacoes !== 0) {
            return false;
        } else {
            if(!$empresa->destroy()) {
                return false;
            }

            return true;
        }


    }

    /**
     * @throws \Exception
     */
    public function edit($empresa, array $data): bool
    {
        // print_r($data);
    	$cnpj = $data['cnpj'];
    	$empresa2 = (new EmpresasModel())->find("cnpj = :cnpj", "cnpj={$data['cnpj']}")->fetch();
        
        
    	
    	if(isset($data['contato-origemnovo'])){
            if($data['contato-origemnovo'] != ""){
                $contatoempresa = new ContatosModel();
                $contatoempresa->responsavel = $data['contato-origemnovo'];
                $contatoempresa->cargo = $data['cargo-origemnovo'];
                $contatoempresa->email = $data['email-origemnovo'];
                $contatoempresa->cnpj = $cnpj;
                $contatoempresa->contato =  $data['telefone-origemnovo'];
                $contatoempresa->user_name = $_SESSION['name'];
                $contatoempresa->empresa = $empresa2->name;
                $contatoempresa->save();
            }
    	
    	}

            $contatos = (new ContatosModel())->find("cnpj = :cnpj", "cnpj={$cnpj}")->fetch(true);
            $key = 0;
            foreach($contatos as $values){
                if(isset($data['contato_id'.$key])){
                    $id = $data['contato_id'.$key];
                    $item = (new ContatosModel())->findById($id);
                    if($data['email-origem'.$key] == ""){
                        $item->destroy();
                    }
                    else{
                        $item->responsavel = $data['contato-origem'.$key];
                        $item->cargo =  $data['cargo-origem'.$key];
                        $item->email =  $data['email-origem'.$key];
                        $item->cnpj =  $cnpj;
                        $item->contato =  $data['telefone-origem'.$key];
                        $item->save();
                    }
		           
                }
                $key++;
            	    
            }
            
            foreach($contatos as $values){
                    
                $id = $values->id_contato_empresa;
                $ok = 0;
                    	
       		    for($i=0;$i<10;$i++){
                    if(isset($data['contato_id'.$i])){
                    
                        
            
                        if($data['contato_id'.$i] == $id){
                       
                                    if(!$data['contato-origem'.$i] == ""){
                                    if(!$data['email-origem'.$i] == $values->email){
                                   
                                    $item2 = (new ContatosModel())->findById($id);
                                    
                                   
                    $item2->responsavel = " ";
                    $item2->cnpj = " ";
                    $item2->cargo =  " ";
                    $item2->email =  " ";
                    $item2->contato =  " ";
                    $item2->status =  "0";
                    $item2->save();
                                }
                            }	
                        }	
                    }
                }

            }
            
        if($data['empresa_name'] !== "") {
            $cotacoes = (new CotacoesModel())->find("cliente = :cliente", "cliente={$empresa->name}")->fetch(true);
            $empresa->name = strtoupper($data['empresa_name']);

            if($cotacoes) {
                foreach ($cotacoes as $cotacao) {
                    $item = (new CotacoesModel())->findById($cotacao->id);
                    $item->cliente = $empresa2->name;
                    $item->save();
                }
            }
        }
        if(isset($data['responsavel'])){
            if($data['responsavel'] !== "") {
                $empresa->responsavel = $data['responsavel'];
            }
        }
        
        if(isset($data['importante'])){
            if($data['importante'] !== "") {
                $empresa->importante = $data['importante'];
            }
        }
        
        if(isset($_POST['apresentacao'])){

            $user = (new Users())->find("id = :id", "id={$_SESSION['id']}")->fetch();;
            $user->apresentacao = $_POST['apresentacao'];
            $user->save();
            
        }  
        if(isset($_POST['assunto'])){

            $user = (new Users())->find("id = :id", "id={$_SESSION['id']}")->fetch();;
            $user->assunto = $_POST['assunto'];
            $user->save();
            
        }   
        if(isset($data['email'])){
            if($data['email'] !== "") {
                $empresa->email = $data['email'];
            }
        }
        
        if($data['city'] !== "") {
            $empresa->cidade = $data['city'];
        }
        if($data['uf'] !== "") {
            $empresa->estado = $data['uf'];
        }
        if($data['rua'] !== "") {
            $empresa->rua = $data['rua'];
        }
        if($data['complemento'] !== "") {
            $empresa->complemento = $data['complemento'];
        }
        if($data['cnpj'] !== "") {
            $empresa->cnpj = $data['cnpj'];
        }

        if($data['vendedor_id'] !== "") {
            $empresa->vendedor_id = $data['vendedor_id'];
        }


        if($data['pagamento'] !== "") {
            $empresa->pagamento = $data['pagamento'];
        }
        if(array_key_exists("contato", $data) && $data['contato'] !== "") {
            $empresa->contato = $data['contato'];
        }
        if(array_key_exists("seguro", $data) && $data['seguro'] !== "") {
            $empresa->seguro = $data['seguro'];
        }
        if(!empty($_FILES['img']) && $_FILES['img']['name'] !== "") {
            $image = new Image("theme/images", "seguro");

            $upload = $image->upload($_FILES['img'], pathinfo($_FILES['img']['name'], PATHINFO_FILENAME));
            $empresa->img = url($upload);
        }

        if(!empty($_FILES['assinatura']) && $_FILES['assinatura']['name'] !== "") {
            $image = new Image("theme/images", "assinatura");
            $upload = $image->upload($_FILES['assinatura'], pathinfo($_FILES['assinatura']['name'], PATHINFO_FILENAME));
            // $user->assinatura = url($upload);
            $user = (new Users())->find("id = :id", "id={$_SESSION['id']}")->fetch();;
            $user->assinatura = url($upload);
            $user->save();
        }

        if(!empty($_FILES['pdfapresentacao']) && $_FILES['pdfapresentacao']['name'] !== "") {
            $image = new File("theme/pdf/apresentacao", "pdfapresentacao");
            $_FILES['pdfapresentacao']['name'] = "apresentacao_petrotrans_transportes_e_logistica.pdf";
            $upload = $image->upload($_FILES['pdfapresentacao'], "APRESENTAÇÃO PETROTRANS - Transportes e Logística");
            $empresa->pdf = "apresentacao_petrotrans_transportes_e_logistica.pdf";
        }     

        $empresa->spc = $data['spc'];
        $empresa->status = $data['status'];

        $contato = (new ContatosModel())->find("empresa = :name", "name={$empresa->name}")->fetch();

        if($contato) {
            $contato->status = $data['status'];
            $contato->save();
        }

        if(!$empresa->save()) {
            return false;
        }

        return true;
    }

}
