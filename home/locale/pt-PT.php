<?php
$txt['app']['platform']="Página Virtual";
$txt['app']['email']="YOUR_EMAIL_ACCOUNT";
$txt['app']['supportMail']="YOUR_EMAIL_ACCOUNT";
$txt['app']['privacy']="Privacidade &amp; Segurança";
$txt['app']['tos']="Termos &amp; Condições";
$txt['app']['copyright']="Todos os Direitos Reservados";
$txt['app']['gotop']="Topo da Página";

$txt['acc']['planPRO']="Pro";
$txt['acc']['planBASIC']="Básico";
$txt['acc']['plan']=(isset($cf['acc']['type']) AND $cf['acc']['type']=='1')?$txt['acc']['planPRO']:$txt['acc']['planBASIC'];

$txt['home']['title']="&lsaquo; {$txt['app']['platform']} &rsaquo; O seu Espaço.NET 24/7.";
$txt['home']['description']="Crie gratuitamente uma Página (sítio) na Internet em apenas alguns minutos. Não precisa de instalar nada e nem sequer precisa de ser especialista.";
$txt['home']['keywords']="grátis, cartão, página, site, website, portal, internet, contacto, emprego, classificado, produto, serviço, actividade, presença, online, espaço, virtual, publicidade, promoção, web design, web development, gráfico";
$txt['home']['copyright']="Todos os Direitos Reservados.";
$txt['home']['avatar']="Logótipo ou Fotografia";

$txt['globar']['title']="&lsaquo; {$txt['app']['platform']} <sup>lab</sup> &rsaquo;";
$txt['globar']['tisep1']='Explorar ( <a href="http://baselocal.com" target="_blank">Base.Local</a> &middot; <a href="http://espacov.com" target="_blank">Espaço Virtual</a> )';
$txt['globar']['tisep2']='Criar ( <a href="http://cartaov.com" target="_blank">Cartão</a> &middot; <a href="http://paginav.com" target="_blank">Página</a> )';
$txt['globar']['updates']="Novidades";
$txt['globar']['help']="Ajuda";
$txt['globar']['edit']="Painel de Controlo";
$txt['globar']['logout']="Sair";
$txt['globar']['search']="OK";

$txt['intro']['title']="&lsaquo; {$txt['app']['platform']} &rsaquo; O seu Espaço.NET 24/7.";
$txt['intro']['base']="Reserve o seu espaço na Internet criando a sua Página Virtual. Grátis. Simples. Imediato.";
$txt['intro']['stats1']="Páginas criadas (ver <a href=\"{$pth['app']['www']}list.php\">lista completa</a>), das quais ";
$txt['intro']['stats2']="por recomendação. Visite as últimas páginas criadas ou actualizadas:";
$txt['intro']['baseDesc']="Esta é a forma mais simples de estar presente na Internet. Eis algumas das razões:";
$txt['intro']['baseDesc1']="Endereço próprio: http://www.paginav.com/<strong>oseunome</strong>";
$txt['intro']['baseDesc2']="Múltiplos idiomas <sup>(1)</sup>";
$txt['intro']['baseDesc3']="Personalização de cores e conteúdo";
$txt['intro']['baseDesc4']="Estatísticas";
$txt['intro']['baseDesc5']="Múltiplas ferramentas de interacção <sup>(1)</sup>";
$txt['intro']['baseDesc6']="Formulário de Contacto";
$txt['intro']['baseDesc7']="Páginas Ilimitadas";
$txt['intro']['baseDesc8']="E muitos € pelo seu \"passa-palavra\" <sup>(1)</sup>";
$txt['intro']['baseDesc9']="<a target=\"_blank\" href=\"http://www.google.com/search?&q=site:paginav.com\">Registo automático</a> nos maiores motores de busca (ex: <a target=\"_blank\" href=\"http://www.google.com/search?&q=site:paginav.com\">Google</a>)";
$txt['intro']['baseFootnote1']="<sup>(1)</sup> Serão adicionadas periodicamente novas funcionalidades.";

$txt['list']['title']="Listagem de Páginas e Membros";
$txt['list']['lastUpdated']="Últimas Páginas Actualizadas";
$txt['list']['lastCreated']="Últimas Páginas Criadas";
$txt['list']['all']="Lista Completa de Páginas Criadas &rsaquo; TOTAL : ";

$txt['lang']['pt-PT']="Português ( Portugal )";
$txt['lang']['en-US']="English ( USA )";

$txt['tool']['home']="Página Inicial";
$txt['tool']['feed']="Subscrever Actualizações";
$txt['tool']['bookmark']="Adicionar aos Favoritos";
$txt['tool']['print']="Imprimir a Página";
$txt['tool']['sendTo']="Enviar para... (BREVEMENTE)";

$txt['searchBaseLocal']['title']="Procurar na Base.Local:";
$txt['searchBaseLocal']['submit']="OK";

$txt['getAccount']['title']="Criar Página (Grátis)";
$txt['getAccount']['name']="Nome da Conta";
$txt['getAccount']['nameFootnote1']="http://www.paginav.com/<strong>NOME</strong>";
$txt['getAccount']['nameFootnote2']="Caracteres (entre 5 e 30)";
$txt['getAccount']['nameFootnote3']="Letras e Números apenas";
$txt['getAccount']['email']="Correio Electrónico <small>(email)</small>";
$txt['getAccount']['emailFootnote1']="Para receber os códigos de acesso e recuperá-los caso se esqueça.";
$txt['getAccount']['refID']="Recomendado por <small>(opcional)</small>";
$txt['getAccount']['refIDFootnote1']="Nome da Conta/Página de quem recomendou <strong>OU</strong> onde viu anunciado este serviço.";
$txt['getAccount']['agreement']="Ao criar a Página estará a aceitar os <a href=\"".$pth['app']['tos']."\">{$txt['app']['tos']}</a> do serviço.";
$txt['getAccount']['submit']="Criar";
$txt['getAccount']['cancel']="Limpar";
$txt['getAccount']['alert1']="O <strong>Nome da Conta</strong> tem de ter entre 5 e 30 caracteres.";
$txt['getAccount']['alert2']="O <strong>Nome da Conta</strong> só poderá conter letras e números, sem caracteres especiais.";
$txt['getAccount']['alert3']="O <strong>Nome da Conta</strong> que escolheu já se encontra reservado.<br/>Por favor, escolha outro.";
$txt['getAccount']['alert4']="Por favor, insira um <strong>email</strong> válido.";

$txt['contactForm']['title']="Deseja falar connosco?";
$txt['contactForm']['alert']="";
$txt['contactForm']['subject']="Assunto";
$txt['contactForm']['subjectAlert']="Assunto é obrigatório. <br/>Mín. 2 caracteres.";
$txt['contactForm']['message']="Mensagem";
$txt['contactForm']['messageAlert']="Mensagem é obrigatória.<br/>Mín. 5, máx. 500 caracteres.";
$txt['contactForm']['name']="O seu Nome";
$txt['contactForm']['nameAlert']="Nome é obrigatório.<br/>Mín. 3 caracteres.";
$txt['contactForm']['email']="O seu Correio Electrónico";
$txt['contactForm']['emailAlert']="Email inválido ou inexistente.";
$txt['contactForm']['sendButton']="Enviar";
$txt['contactForm']['resetButton']="Limpar";
$txt['contactForm']['messageIntro']="[ {$txt['app']['platform']} - Formulário de Contacto ]";
$txt['contactForm']['sent']="Mensagem enviada com sucesso.";
$txt['contactForm']['notsent']="Não foi possível enviar a mensagem.";

$txt['err404']['title']="ERRO: Página Inexistente.";
$txt['err404']['description']="<p>A página que solicitou não existe. Recomendamos que:</p><ul><li>Procure no endereço possíveis erros tipográficos;</li><li>Volte à <a href='javascript:history.back()'>página anterior</a>.</li></ul>";
?>