<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>Sistema de Cadastro</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>
	<div class='container box-mensagem-crud'>
		<?php 
		require 'conexao.php';
		// Atribui uma conexão PDO
		$conexao = conexao::getInstance();
		// Recebe os dados enviados pela submissão
		$acao  = (isset($_POST['acao'])) ? $_POST['acao'] : '';
		$idProfessor    = (isset($_POST['idProfessor'])) ? $_POST['idProfessor'] : '';
		$nome  = (isset($_POST['nome'])) ? $_POST['nome'] : '';
		$nomeartistico  = (isset($_POST['nomeartistico'])) ? $_POST['nomeartistico'] : '';
		$endereco  = (isset($_POST['endereco'])) ? $_POST['endereco'] : '';
		$rg   = (isset($_POST['rg'])) ? str_replace(array('.','-'), '', $_POST['rg']): '';
		$cpf   = (isset($_POST['cpf'])) ? str_replace(array('.','-'), '', $_POST['cpf']): '';
		$cnpj   = (isset($_POST['cnpj'])) ? str_replace(array('.','-'), '', $_POST['cnpj']): '';
		$data_nascimento  = (isset($_POST['data_nascimento'])) ? $_POST['data_nascimento'] : '';		
		$email = (isset($_POST['email'])) ? $_POST['email'] : '';
		$foto_atual		  = (isset($_POST['foto_atual'])) ? $_POST['foto_atual'] : '';
		$telefone  		  = (isset($_POST['telefone'])) ? str_replace(array('-', ' '), '', $_POST['telefone']) : '';
		$celular   		  = (isset($_POST['celular'])) ? str_replace(array('-', ' '), '', $_POST['celular']) : '';
		
$categoria = (isset($_POST['categoria'])) ? $_POST['categoria'] : '';
		$status = (isset($_POST['status'])) ? $_POST['status'] : '';
	

	// Valida os dados recebidos
		$mensagem = '';
		if ($acao == 'editar' && $idProfessor == ''):
		    $mensagem .= '<li>idProfessor do registros desconhecido.</li>';
	    endif;

	    // Se for ação diferente de excluir valida os dados obrigatórios
	    if ($acao != 'excluir'):
			if ($nome == '' || strlen($nome) < 3):
				$mensagem .= '<li>Favor preencher o Nome.</li>';
		    endif;

			if ($cpf == ''):
			   $mensagem .= '<li>Favor preencher o CPF.</li>';
		    elseif(strlen($cpf) < 11):
				  $mensagem .= '<li>Formato do CPF inválido.</li>';
		    endif;

			if ($email == ''):
				$mensagem .= '<li>Favor preencher o E-mail.</li>';
			elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)):
				  $mensagem .= '<li>Formato do E-mail inválido.</li>';
			endif;

			/*if ($data_nascimento == ''): 		
				$mensagem .= '<li>Favor preencher a Data de Nascimento.</li>';
			else:
				$data = explode('/', $data_nascimento);
				if (!checkdate($data[1], $data[0], $data[2])):
					$mensagem .= '<li>Formato da Data de Nascimento inválido.</li>';
				endif;
			endif;*/

			if ($telefone == ''): 
				$mensagem .= '<li>Favor preencher o Telefone.</li>';
			elseif(strlen($telefone) < 10):
				  $mensagem .= '<li>Formato do Telefone inválido.</li>';
		    endif;

			if ($celular == ''):
				$mensagem .= '<li>Favor preencher o Celular.</li>';
			elseif(strlen($celular) < 11):
				  $mensagem .= '<li>Formato do Celular inválido.</li>';
			endif;

			if ($status == ''):
			   $mensagem .= '<li>Favor preencher o Status.</li>';
			endif;

			if ($mensagem != ''):
				$mensagem = '<ul>' . $mensagem . '</ul>';
				echo "<div class='alert alert-danger' role='alert'>".$mensagem."</div> ";
				exit;
			endif;
		// Constrói a data no formato ANSI yyyy/mm/dd
		// 	$data_temp = explode('/', $data_nascimento);
		// 	$data_ansi = $data_temp[2] . '/' . $data_temp[1] . '/' . $data_temp[0];
		endif;

	// Verifica se foi solicitada a inclusão de dados
		if ($acao == 'incluir'):

			$nome_foto = 'padrao.jpg';
			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0):  

				$extensoes_aceitas = array('bmp' ,'png', 'svg', 'jpeg', 'jpg');
			    $extensao = strtolower(end(explode('.', $_FILES['foto']['name'])));

			     // Validamos se a extensão do arquivo é aceita
			    if (array_search($extensao, $extensoes_aceitas) === false):
			       echo "<h1>Extensão Inválida!</h1>";
			       exit;
			    endif;
 
			     // Verifica se o upload foi enviado via POST   
			     if(is_uploaded_file($_FILES['foto']['tmp_name'])):  
			             
			          // Verifica se o diretório de destino existe, senão existir cria o diretório  
			          if(!file_exists("fotos")):  
			               mkdir("fotos");  
			          endif;  
			  
			          // Monta o caminho de destino com o nome do arquivo  
			          $nome_foto = date('dmY') . '_' . $_FILES['foto']['name'];  
			            
			          // Essa função move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;  
			endif;

	$sql = 'INSERT INTO tab_professor (nome, nomeartistico, endereco, rg, cpf, cnpj, data_nascimento, email, telefone, celular, status, foto, categoria)
							   VALUES(:nome, :nomeartistico, :endereco, :rg, :cpf, :cnpj, :data_nascimento, :email, :telefone, :celular, :status, :foto, :categoria)';

			$stm = $conexao->prepare($sql);
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':nomeartistico', $nomeartistico);
			$stm->bindValue(':endereco', $endereco);
			$stm->bindValue(':rg', $rg);
			$stm->bindValue(':cpf', $cpf);
			$stm->bindValue(':cnpj', $cnpj);
			$stm->bindValue(':data_nascimento', $data_nascimento);
			$stm->bindValue(':email', $email);
			$stm->bindValue(':telefone', $telefone);
			$stm->bindValue(':celular', $celular);
			$stm->bindValue(':categoria', $categoria);
			$stm->bindValue(':status', $status);
			$stm->bindValue(':foto', $nome_foto);
			$retorno = $stm->execute();
			if ($retorno):
				
				echo "<div class='alert alert-success' role='alert'>Todos os dados inseridos com sucesso</div> ";
				
				
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao inserir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='3;URL=cadastro_artista.php'>";
			

		endif;
	  	

		// Verifica se foi solicitada a edição de dados
		if ($acao == 'editar'):

			if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0): 

				// Verifica se a foto é diferente da padrão, se verdadeiro exclui a foto antiga da pasta
				if ($foto_atual <> 'padrao.jpg'):
					unlink("fotos/" . $foto_atual);
				endif;

				$extensoes_aceitas = array('bmp' ,'png', 'svg', 'jpeg', 'jpg');
			    $extensao = strtolower(end(explode('.', $_FILES['foto']['name'])));

			     // Validamos se a extensão do arquivo é aceita
			    if (array_search($extensao, $extensoes_aceitas) === false):
			       echo "<h1>Extensão Inválida!</h1>";
			       exit;
			    endif;
 
			     // Verifica se o upload foi enviado via POST   
			     if(is_uploaded_file($_FILES['foto']['tmp_name'])):  
			             
			          // Verifica se o diretório de destino existe, senão existir cria o diretório  
			          if(!file_exists("fotos")):  
			               mkdir("fotos");  
			          endif;  
			  
			          // Monta o caminho de destino com o nome do arquivo  
			          $nome_foto = date('dmY') . '_' . $_FILES['foto']['name'];  
			            
			          // Essa função move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
			          if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'fotos/'.$nome_foto)):  
			               echo "Houve um erro ao gravar arquivo na pasta de destino!";  
			          endif;  
			     endif;
			
			else:

			 	$nome_foto = $foto_atual;
			endif;

			$sql = 'UPDATE tab_professor SET nome=:nome, nomeartistico=:nomeartistico, endereco=:endereco, rg=:rg, cpf=:cpf, cnpj=:cnpj, data_nascimento=:data_nascimento, email=:email, telefone=:telefone, celular=:celular, categoria=:categoria, status=:status, foto=:foto WHERE idProfessor = :idProfessor';
			
			$stm = $conexao->prepare($sql);
			
			$stm->bindValue(':nome', $nome);
			$stm->bindValue(':nomeartistico', $nomeartistico);
			$stm->bindValue(':endereco', $endereco);
			$stm->bindValue(':rg', $rg);
			$stm->bindValue(':cpf', $cpf);
			$stm->bindValue(':cnpj', $cnpj);
			$stm->bindValue(':data_nascimento', $data_nascimento);
			$stm->bindValue(':email', $email);
			$stm->bindValue(':telefone', $telefone);
			$stm->bindValue(':celular', $celular);
			$stm->bindValue(':categoria', $categoria);
			$stm->bindValue(':status', $status);
			$stm->bindValue(':foto', $nome_foto);
			$stm->bindValue(':idProfessor', $idProfessor);
			$retorno = $stm->execute();
			if ($retorno):
				echo "<div class='alert alert-success' role='alert'>Registro editado com sucesso, aguarde você está sendo redirecionado ...</div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao editar registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='3;URL=cadastro_artista.php'>";
		endif;

		
		// Verifica se foi solicitada a exclusão dos dados
		if ($acao == 'excluir'):

			// Captura o nome da foto para excluir da pasta
			$sql = "SELECT foto FROM tab_professor WHERE idProfessor = :idProfessor AND foto <> 'padrao.jpg'";
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':idProfessor', $idProfessor);
			$stm->execute();
			$cliente = $stm->fetch(PDO::FETCH_OBJ);

			if (!empty($cliente) && file_exists('fotos/'.$cliente->foto)):
				unlink("fotos/" . $cliente->foto);
			endif;

			// Exclui o registro do banco de dados
			$sql = 'DELETE FROM tab_professor WHERE idProfessor = :idProfessor';
			$stm = $conexao->prepare($sql);
			$stm->bindValue(':idProfessor', $idProfessor);
			$retorno = $stm->execute();

			if ($retorno):
				echo "<div class='alert alert-success' role='alert'>Registro excluído com sucesso, aguarde você está sendo redirecionado ...</div> ";
		    else:
		    	echo "<div class='alert alert-danger' role='alert'>Erro ao excluir registro!</div> ";
			endif;

			echo "<meta http-equiv=refresh content='3;URL=index.php'>";
		endif;
	?>
	</div>
</body>
</html>