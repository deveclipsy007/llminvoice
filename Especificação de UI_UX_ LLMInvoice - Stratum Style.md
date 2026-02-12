# **🛸 LLMInvoice: Guia de Design System (Stratum Style)**

Este documento detalha as diretrizes visuais e de interface para a plataforma **LLMInvoice**, baseando-se na estética *High-Tech Minimalist* do projeto Stratum AI.

## **1\. Visão Geral e Conceito**

A interface deve passar a sensação de uma **"Sala de Comando Inteligente"**. Como o motor é uma IA que decide e automatiza o pipeline, o design deve ser limpo para não sobrecarregar o usuário, mas rico em detalhes visuais (glows e profundidade) para destacar a tecnologia.

* **Atributos:** Precisão, Automação, Fluidez e Sofisticação.  
* **Estilo Base:** Bento Grid, Glassmorphism e Dark Mode Profundo.

## **2\. Identidade Visual (Design Tokens)**

### **2.1 Paleta de Cores (The "Lime-Dark" Palette)**

| Categoria | HEX | Aplicação |
| :---- | :---- | :---- |
| **Deep Dark** | \#050505 | Fundo principal (Background). |
| **Surface** | \#111111 | Cards, modais e áreas de conteúdo. |
| **Electric Lime** | \#C8FF00 | CTAs, indicadores de status positivos, IA ativa. |
| **Text Primary** | \#FFFFFF | Títulos e leitura principal. |
| **Text Secondary** | \#888888 | Legendas e metadados. |
| **Danger/Alert** | \#FF4B4B | Erros ou leads perdidos. |

### **2.2 Tipografia**

* **Fonte Principal:** Aeonik Pro (ou Plus Jakarta Sans como alternativa Google Fonts).  
* **Configuração de Títulos:** \* Peso: 600 (Semibold) ou 700 (Bold).  
  * Letter Spacing: \-0.02em (para um visual mais compacto e moderno).  
* **Corpo de Texto:** 400 (Regular), cor \#D1D1D1.

## **3\. UI Components (Aplicações para LLMInvoice)**

### **3.1 O Kanban "Inteligente"**

Diferente de um Kanban comum, o do LLMInvoice deve parecer um painel de controle de alta tecnologia.

* **Cards de Lead:** Devem ter um indicador de "AI Confidence" (um pequeno badge com glow verde).  
* **Transições:** Ao mover um lead, use uma animação de "pulse" verde limão na coluna de destino.  
* **Visual:** Bordas arredondadas (rounded-3xl) e fundo semi-transparente com backdrop-blur-md.

### **3.2 Propostas Automatizadas (Visual de Documento)**

A prévia das propostas deve seguir a seção clara do Stratum AI:

* Fundo: \#F2F4F1 (Sage Light).  
* Tipografia: Preto puro para máxima legibilidade.  
* Assinatura Digital: Onde o contrato é assinado, use um campo com glow sutil para chamar a atenção.

### **3.3 Bento Grid Dashboard**

A tela principal de métricas deve usar o layout de "caixas" de tamanhos variados.

* **Caixa de IA:** Um widget maior que mostra as "Ações Recomendadas pela IA" com um gradiente animado no fundo.  
* **Pipeline Health:** Um gráfico de barras ou donut com as cores Neon Lime.

## **4\. Efeitos e Materiais**

### **4.1 Glassmorphism (Vidro)**

Para os componentes que flutuam sobre o fundo escuro:

.glass-panel {  
  background: rgba(18, 18, 18, 0.7);  
  backdrop-filter: blur(16px);  
  border: 1px solid rgba(255, 255, 255, 0.08);  
  border-radius: 24px;  
}

### **4.2 Bordas de Luz (Edge Glow)**

Use uma borda interna sutil para dar volume aos cards:

/\* No Tailwind \*/  
border-t border-l border-white/10 border-b border-r border-black/50

## **5\. Implementação Técnica (Stack)**

* **Frontend:** React (Next.js 14+ recomendado).  
* **Estilização:** Tailwind CSS.  
* **Animações:** Framer Motion.  
  * *Exemplo:* Os cards de leads devem "entrar" com um efeito de *Stagger* (um por um) com opacidade e subida sutil.  
* **Ícones:** Lucide React (Stroke: 1.5px).  
* **Gráficos:** Tremor.so (biblioteca de gráficos já otimizada para dashboards dark/modernos).

## **6\. Representação da IA (Core do LLMInvoice)**

A inteligência artificial não deve ser apenas um texto. Ela deve ser um **elemento visual**:

1. **Orb Animada:** No topo da tela ou ao lado de campos que a IA está analisando, use um gradiente circular pulsante (\#C8FF00 com desfoque).  
2. **Streaming Text:** Quando a IA gera uma proposta ou análise, use o efeito de "digitação" para mostrar que o motor está trabalhando em tempo real.  
3. **Tags de Insights:** Use badges com bg-\[\#C8FF00\]/10 e text-\[\#C8FF00\] para insights automáticos sobre leads (ex: "Lead Quente", "Decisor Identificado").

## **7\. Próximos Passos**

1. Configurar as cores no tailwind.config.js.  
2. Criar o componente Card base com o efeito de vidro.  
3. Implementar o layout Bento para a Dashboard.  
4. Definir as animações de transição de colunas no Kanban.