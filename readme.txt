=== Juno Split ===
Contributors: marcofrasson, luismatias, amgnando
Donate link: https://juno.com.br
Tags: split, boleto, boleto bancario, credit card, gateway, pagamento, woocommerce
Requires at least: 5.0
Tested up to: 5.7.0
Stable tag: trunk
Requires PHP: 7.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add Juno Split to WooCommerce

== Description ==

Este plugin adiciona o split de pagamento da Juno aos plugins Dokan, WC Vendors e Product Vendors.

= ATENÇÃO =

Para o funcionamento do split, é necessário utilizar o [plugin base da Juno](https://wordpress.org/plugins/woo-juno/) e a versão 2 da API da Juno.

O vendedor do seu marketplace precisará ter uma conta Juno e gerar um token privado para adicionar nas configurações de sua conta dentro do Dokan, Product Vendors ou WC Vendors. Só a partir disso é que o split irá funcionar automaticamente.

O split funciona para boleto bancário e cartão de crédito.

= DOKAN =

Veja a [demonstração](https://demo.goflow.digital/juno_dokan/) com o Dokan.

Como o vendedor irá configurar:

1 - Para configurar o Dokan, precisa acessar a página "Dashboard" do menu frontend.
2 - Depois, acessar o menu de Settings;
3 - E depois o menu de Payments;
4 - Inserir o Token Privado da conta do vendedor nesse campo;
5 - Salvar as configurações.

= WCFM =

Veja a [demonstração](https://demo.goflow.digital/juno_wcfm/) com o WCFM Marketplace.

Como o vendedor irá configurar:

1 - Para configurar o WCFM, precisa acessar a página "Store Manager" do menu frontend.
2 - Depois, acessar o menu de Configurações;
3 - E depois o menu de Pagamentos;
4 - Inserir o Token Privado da conta do vendedor nesse campo;
5 - Salvar as configurações.

= PRODUCT VENDORS =

Veja a [demonstração](https://demo.goflow.digital/juno_productvendors/) com o Product Vendors.

Como o vendedor irá configurar:

1- Para configurar o Product Vendors, o vendedor precisa entrar na conta e depois no menu "Store Settings"
2 - Ao fim da página tem o campo para inserir o Token Privado do vendedor;
3 - Salvar as configurações.

= WC VENDORS =

Veja a [demonstração](https://demo.goflow.digital/juno_wcvendors/) com o WC Vendors.

Como o vendedor irá configurar:

1 - Para configurar o WC Vendors, é necessário acessar o menu "Vendedor Dashboard > Store Settings"
2 - Nessa página tem o campo para inserir o Token Privado do vendedor;
3 - Salvar as configurações.


= Como a Juno funciona =

Os seguintes recursos estão presentes nesse plugin:

1. Você emite cobranças via cartão de crédito e boleto bancário através da Juno, tudo de forma integrada com o seu e-commerce.
2. O seu cliente faz o pagamento no seu checkout.
3. O valor entra em nossa conta operacional.
4. Transferimos os valores para qualquer conta bancária de sua escolha.

= Quem é a Juno? =

Nós somos uma fintech especializada em pagamentos integrados para e-commerces e marketplaces. Com a Juno, você pode vender através de boleto bancário e cartão de crédito sem nenhuma dor de cabeça, já que nós temos sempre de plantão uma equipe de suporte para te atender.

Para a Juno, o sucesso da sua empresa é super importante, já que a nossa receita depende única e exclusivamente dele. Nós só cobramos por pagamento recebido, então se você não concluir uma venda, a gente não te cobra nada por isso.

Quer conhecer mais sobre a gente? [Clica aqui :)](https://juno.com.br/)

= Recursos =

- Split de pagamento para o plugin Dokan
- Split de pagamento para o plugin Product Vendors
- Split de pagamento para o plugin WC Vendors

= Instalação =

Confira o nosso guia de instalação e configuração na aba [Installation](https://wordpress.org/plugins/juno-split/#installation).

= Compatibilidade =

Requer o Juno para WooCommerce 2.0 ou posterior para funcionar.
Requer WooCommerce 3.0 ou posterior para funcionar.
Requer Brazilian Market on Woocommerce para funcionar.

= Dúvidas? =

Você pode esclarecer suas dúvidas usando:

- A nossa sessão de [FAQ](http://wordpress.org/plugins/juno-split/#faq);
- Criando um tópico no [fórum de ajuda do WordPress](http://wordpress.org/support/plugin/juno-split);
- Você pode entrar em contato com a gente pelo nosso chat no [site](https://juno.com.br/contato.html) ou telefone 41 3013-9650.

== Installation ==

= Para usar o Split Juno: =

- Você precisará instalar o plugin base da Juno para WooCommerce e utilizar a versão 2 da API da Juno.

= Instalação do plugin: =

- Envie os arquivos do plugin para a pasta wp-content/plugins, ou instale usando o instalador de plugins do WordPress.
- Ative o plugin.

= Requerimentos: =

- Requer o Juno para WooCommerce 2.0 ou posterior para funcionar.
- Requer WooCommerce 3.0 ou posterior para funcionar.
- Requer Brazilian Market on Woocommerce para funcionar.
- PHP 7.0 ou superior.

= Configurações do plugin: =

Para configurar o Juno Split, acesse a aba do "WooCommerce" > "Configurações" > "Integração" > "Juno". Cada plugin terá as configurações para o vendedor inserir o token dele.

== Frequently Asked Questions ==

= Qual é a licença do plugin? =

Este plugin esta licenciado como GPL.

= O que eu preciso para utilizar este plugin? =

* Juno para WooCommerce 2.0 ou posterior.
* Credenciais da API v2 da Juno.
* WooCommerce 3.0 ou posterior.
* Brazilian Market on Woocommerce

= Como o vendedor cadastra a conta dele? =

O vendedor do seu marketplace precisará ter uma conta Juno e gerar um token privado para adicionar nas configurações de sua conta dentro do Dokan, Product Vendors ou WC Vendors. Só a partir disso é que o split irá funcionar automaticamente.

= Como colocar comissão de venda? =

As comissões funcionam dentro da configuração de cada plugin de marketplace. O Juno Split apenas irá verificar a comissão e distribuir para o adminstrador do marketplace e para o vendedor.

= Consigo habilitar somente o cartão de crédito ou boleto? =

Sim, você poderá habilitar os dois meios de pagamento ou apenas um deles, na configuração padrão do WooCommerce em "WooCommerce" > "Configurações" > "Pagamentos"

= Possui mais dúvidas sobre a Juno? =

Você poderá entrar em contato diretamente conosco pelo [nosso site](https://juno.com.br/contato.html) ou telefone 41 3013-9650.

== Screenshots ==

1. Checkout transparente para cartão de crédito com efeito visual.
2. Possibilidade do cliente selecionar cartão salvo no checkout.
3. Boleto bancário à vista ou parcelado.
4. Página de agradecimento com código de barras visual e linha digitável para o cliente.
5. Página de configuração da integração com a Juno.
6. Configuração dos meios de pagamento.
7. Página de configuração do boleto bancário da Juno.
8. Página de configuração do cartão de crédito da Juno.
9. Widget no admin com o saldo da sua conta na Juno.

== Changelog ==

= 1.1.4 - 2021/03/29 =

- Melhoria no arredondamento no momento da divisão

= 1.1.3 - 2020/12/15 =

- Prevenir que o token do vendedor fique em branco em alguns casos.

= 1.1.2 - 2020/11/12 =

- Ajustando as divisões em alguns casos de parcelamento

= 1.1.1 - 2020/08/28 =

- Correção de bugs.

= 1.1.0 - 2020/08/27 =

- Integração com plugin WCFM.

= 1.0.0 - 2020/08/19 =

- Lançamento do plugin.

== Upgrade Notice ==

= 1.0.0 - 2020/08/19  =

- Lançamento do plugin.

