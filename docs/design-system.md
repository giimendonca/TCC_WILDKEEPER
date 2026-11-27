# Design System | WildKeeper

Este documento registra as referências visuais compartilhadas do projeto. O arquivo `assets/css/reset.css` é a referência principal para as variáveis globais de cor.

## Cores

| Nome | Valor atual no CSS | Uso |
|---|---|---|
| Verde principal | `#2E7D32` | Identidade, navegação e ações principais |
| Verde claro | `#81C784` | Destaques e estados de interação |
| Azul | `#1565C0` | Ações secundárias e edição |
| Cinza | `#ECEFF1` | Fundos e superfícies neutras |
| Branco | `#FFFFFF` | Superfícies e texto sobre fundos escuros |
| Erro/Sair | `#D32F2F` | Erros e ações destrutivas |
| Sucesso | `#388E3C` | Confirmações |
| Texto | `#263238` | Texto principal |

**Atenção:** a tonalidade do verde claro deve ser conferida visualmente na versão final. O valor acima corresponde ao encontrado em `assets/css/reset.css` no repositório consultado; se a decisão visual mais recente for diferente, atualizar primeiro o CSS e depois este documento.

## Tipografia

- Fonte principal identificada: Poppins.
- A inclusão da fonte é centralizada em `src/includes/fonte.php`.
- Evitar misturar famílias tipográficas sem necessidade.

## Componentes e padrões

- **Botão primário:** verde principal.
- **Ação de edição:** azul.
- **Ação destrutiva:** vermelho.
- **Campos:** manter rótulos associados aos controles, estados de foco perceptíveis e espaçamento consistente.
- **Cards:** priorizar fundo claro, hierarquia clara de título e informação.
- **Navegação interna:** cabeçalho e menu compartilhados por meio dos includes do dashboard.
- **Ícones:** o projeto utiliza Font Awesome nas páginas que já foram atualizadas; não documentar Material Symbols como biblioteca padrão atual.

## Espaçamento e acabamento

A escala de referência documentada é 8, 16, 24, 32 e 48 px. Manter consistência entre margens, preenchimentos e distâncias entre componentes.

- Raio de borda de referência: 8 px.
- Sombra de referência: `0 4px 12px rgba(0,0,0,.1)`.

## Responsividade e acessibilidade

- Utilizar a viewport adequada em todas as páginas.
- Evitar rolagem horizontal em larguras menores.
- Manter contraste suficiente entre texto e fundo.
- Usar textos alternativos em imagens informativas.
- Não depender exclusivamente da cor para comunicar estados.

## Fonte da verdade

Antes de alterar a identidade visual, conferir `assets/css/reset.css` e os estilos específicos em `assets/css/`. Este documento deve acompanhar os valores realmente utilizados, não valores planejados que já tenham sido substituídos.
