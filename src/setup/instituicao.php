<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Instituição | WildKeeper</title>
</head>

<body>
    <?php include "../includes/header.php" ?>
    <main>
        <section id="formCadastro">
            <form action="salvar_instituicao.php" method="post">
                <h1>Cadastro da Instituição</h1>
                <p>Preencha as informações abaixo para criar sua instituição e o primeiro usuário administrador.</p>

                <fieldset>
                    <legend>Dados da Instituição</legend>

                    <label for="nome_instituicao">Nome da Instituição</label>
                    <input type="text" name="nome_instituicao" id="nome_instituicao" maxlength="100" placeholder="Digite o nome da intituição" required>

                    <label for="cnpj">CNPJ</label>
                    <input type="text" name="cnpj" id="cnpj" maxlength="18" placeholder="00.000.000/0000-00" required>

                    <label for="telefone_instituicao">Telefone</label>
                    <input type="text" name="telefone_instituicao" id="telefone_instituicao" maxlength="20" placeholder="(11) 99999-9999" required>

                    <label for="email_instituicao">Email</label>
                    <input type="email" name="email_instituicao" id="email_instituicao" maxlength="100" placeholder="ex.: suaInstituicao@email.com" required>

                    <label for="website">Website</label>
                    <input type="url" name="website" id="website" maxlength="255" placeholder="https://www.seusite.com.br" required>
                </fieldset>
                <fieldset>
                    <legend>Endereço</legend>

                    <!-- Futuramente os campos abaixo serão preenchidos automaticamente pela API ViaCEP -->
                    <label for="cep">CEP</label>
                    <input type="text" name="cep" id="cep" maxlength="9" placeholder="ex.: 99999-999" required>

                    <label for="rua">Rua</label>
                    <input type="text" name="rua" id="rua" maxlength="150" placeholder="Digite o nome da sua rua" required>

                    <label for="numero">Número</label>
                    <input type="text" name="numero" id="numero" maxlength="20" placeholder="ex.: 999" required>

                    <label for="bairro">Bairro</label>
                    <input type="text" name="bairro" id="bairro" maxlength="80" placeholder="Digite o nome do seu bairro" required>

                    <label for="cidade">Cidade</label>
                    <input type="text" name="cidade" id="cidade" maxlength="80" placeholder="Digite o nome da sua cidade" required>

                    <label for="estado">Estado</label>
                    <input type="text" name="estado" id="estado" maxlength="2" placeholder="Digite a sigla do seu estado" required>
                </fieldset>
                <fieldset>
                    <legend>Administrador</legend>

                    <label for="nome_adm">Nome do Administrador</label>
                    <input type="text" name="nome_adm" id="nome_adm" maxlength="100" placeholder="Digite o nome do usuário ADM" required>

                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" id="cpf" maxlength="14" placeholder="000.000.000-00" required>

                    <label for="telefone_adm">Telefone</label>
                    <input type="text" name="telefone_adm" id="telefone_adm" maxlength="20" placeholder="(11) 99999-9999" required>


                    <label for="email_adm">Email</label>
                    <input type="email" name="email_adm" id="email_adm" maxlength="100" placeholder="ex.: administrador@email.com" required>

                    <label for="data_nascimento">Data Nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" required>

                    <label for="genero">Genêro</label>
                    <select name="genero" id="genero" required>
                        <option value="">Selecione...</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                        <option value="Não-binário">Não-binário</option>
                        <option value="Outro">Outro</option>
                        <option value="Prefiro não informar">Prefiro não informar</option>
                    </select>

                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" minlength="6" placeholder="Digite uma senha" autocomplete="new-password" required>

                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" name="confirmar_senha" id="confirmar_senha" minlength="6" placeholder="Confirme a senha" autocomplete="new-password" required>
                </fieldset>

                <div class="checkbox-container">
                    <input type="checkbox" id="aceite" name="aceite" required>

                    <label for="aceite">
                        Confirmo que sou responsável pela instituição e autorizo o cadastro dos dados informados.
                    </label>
                </div>

                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>
    <?php include "../includes/footer.php" ?>
</body>

</html>