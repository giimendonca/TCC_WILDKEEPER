# Módulos do WildKeeper

Este documento descreve os módulos identificados na implementação atual do sistema. A organização funcional foi ajustada durante o desenvolvimento; por isso, nem todos os módulos inicialmente planejados permaneceram no escopo.

## 1. Dashboard
Painel inicial com indicadores da instituição, incluindo animais, espécies, habitats, funcionários, consultas e eventos. Também apresenta informações sobre eventos por situação e tipo, próximos eventos, indicadores de saúde dos animais e alertas de estoque de medicamentos.

## 2. Autenticação e controle de acesso
Login e logout de usuários, gerenciamento de sessão e controle de funcionalidades conforme o nível do cargo. O login possui limitação de tentativas por combinação de e-mail/IP e por IP.

## 3. Instituição
Cadastro inicial da instituição e do usuário administrador. Após a configuração inicial, permite consultar e gerenciar os dados da instituição vinculada à conta.

## 4. Funcionários
Cadastro e consulta dos funcionários vinculados à instituição, com informações pessoais, cargo e situação funcional. O acesso às operações é condicionado ao nível de permissão.

## 5. Espécies
Cadastro e consulta de espécies, com informações como nome popular, nome científico, origem, expectativa de vida, medidas médias, categoria, classificação alimentar e risco de extinção.

## 6. Habitats
Cadastro e gerenciamento dos habitats da instituição, incluindo bioma, temperatura, umidade, capacidade e situação.

## 7. Animais
Cadastro e gerenciamento dos animais, vinculando cada registro à espécie, ao habitat e à instituição. Inclui dados de identificação, características, situação e informações de saúde. O sistema também permite gerenciar fotos associadas aos animais.

## 8. Eventos
Módulo central para organização de atividades da instituição. Os eventos possuem título, descrição, período, responsável, situação e tipo. Os tipos atualmente previstos no banco são Consulta, Vacinação, Manutenção, Transferência e Outro.

As associações dependem do tipo de evento: consultas e vacinações se relacionam a animais; manutenções se relacionam a habitats; transferências relacionam animais e habitats.

## 9. Consultas veterinárias
Registro e consulta de atendimentos veterinários vinculados a eventos. Permite registrar diagnóstico, tratamento, observações, data de retorno e medicamentos prescritos.

## 10. Medicamentos
Controle do estoque de medicamentos por instituição, com nome, descrição, fabricante, quantidade, lote e vencimento. Os medicamentos podem ser associados às consultas com dosagem e observações.

## 11. Vacinas
Acompanhamento de eventos de vacinação, com registro da vacina aplicada, data de aplicação, próxima aplicação e observações.

## 12. Manutenções de habitats
Acompanhamento de eventos de manutenção associados aos habitats, incluindo descrição e funcionário responsável.

## 13. Histórico de habitats
Registro das mudanças de habitat dos animais, com habitat anterior, novo habitat, data, motivo, funcionário e evento relacionado.

---

## Funcionalidades que não fazem parte do escopo atual

- **Alimentação:** o módulo foi descontinuado e a tabela `alimentacoes` não faz parte do banco atual.
- **Relatórios:** não foi identificado um módulo independente de emissão de relatórios na navegação atual.
- **Configurações gerais:** as configurações institucionais são tratadas no módulo da instituição; não há um módulo geral independente identificado.

> Antes da entrega, conferir este documento com a navegação e as permissões da versão que será apresentada.
