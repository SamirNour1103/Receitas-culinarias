<?php
// funcoes.php - funções compartilhadas (upload de imagem e blocos de HTML)

if (!defined('BASE_URL')) {
    define('BASE_URL', '/receitas-culinarias/');   // pasta do projeto no servidor
}
define('PASTA_UPLOAD', __DIR__ . '/uploads/receitas/');
define('TAMANHO_MAX_IMAGEM', 2 * 1024 * 1024);      // 2 MB

/**
 * Valida e salva a imagem enviada.
 * Retorna o nome do arquivo salvo, ou null (sem imagem OU erro).
 * Em caso de erro, $erro recebe a mensagem.
 */
function salvar_imagem($arquivo, &$erro)
{
    $erro = null;

    if (!$arquivo || $arquivo['error'] === UPLOAD_ERR_NO_FILE) {
        return null;   // imagem é opcional
    }
    if ($arquivo['error'] === UPLOAD_ERR_INI_SIZE || $arquivo['error'] === UPLOAD_ERR_FORM_SIZE) {
        $erro = "A imagem é maior que o limite permitido (2 MB).";
        return null;
    }
    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        $erro = "Erro ao enviar a imagem. Tente novamente.";
        return null;
    }
    if ($arquivo['size'] > TAMANHO_MAX_IMAGEM) {
        $erro = "A imagem deve ter no máximo 2 MB.";
        return null;
    }

    // Confere o tipo REAL do arquivo (não confia na extensão enviada)
    $permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($arquivo['tmp_name']);

    if (!isset($permitidos[$mime]) || @getimagesize($arquivo['tmp_name']) === false) {
        $erro = "Envie uma imagem JPG, PNG ou WEBP.";
        return null;
    }

    if (!is_dir(PASTA_UPLOAD)) {
        mkdir(PASTA_UPLOAD, 0755, true);
    }

    // Nome aleatório: evita conflito e nomes maliciosos
    $nome = bin2hex(random_bytes(12)) . '.' . $permitidos[$mime];

    if (!move_uploaded_file($arquivo['tmp_name'], PASTA_UPLOAD . $nome)) {
        $erro = "Não foi possível salvar a imagem no servidor.";
        return null;
    }
    return $nome;
}

function apagar_imagem($nome)
{
    if ($nome && is_file(PASTA_UPLOAD . basename($nome))) {
        unlink(PASTA_UPLOAD . basename($nome));
    }
}

/** <img> da receita, ou um quadro com ícone quando não há foto. */
function tag_imagem($nome, $alt, $classe = '')
{
    if ($nome) {
        return '<img class="' . $classe . '" src="' . BASE_URL . 'uploads/receitas/' . rawurlencode($nome)
            . '" alt="' . htmlspecialchars($alt) . '" loading="lazy">';
    }
    return '<div class="' . $classe . ' sem-imagem" aria-hidden="true"><i class="fa-solid fa-utensils"></i></div>';
}

/** Cartão de avaliação clicável (abre o modal em tela cheia). */
function render_avaliacao($a, $receita_id, $receita_nome)
{
    $score = (int) $a['score'];
    $estrelas = str_repeat('★', $score) . str_repeat('☆', 5 - $score);
    ?>
    <article class="avaliacao clicavel" tabindex="0" role="button"
        aria-label="Abrir avaliação de <?php echo htmlspecialchars($a['usuario']); ?>"
        data-receita="<?php echo htmlspecialchars($receita_nome); ?>" data-receita-id="<?php echo (int) $receita_id; ?>"
        data-usuario="<?php echo htmlspecialchars($a['usuario']); ?>"
        data-data="<?php echo htmlspecialchars($a['data']); ?>" data-estrelas="<?php echo $estrelas; ?>"
        data-texto="<?php echo htmlspecialchars($a['descricao']); ?>">
        <div class="avaliacao-topo">
            <strong><?php echo htmlspecialchars($a['usuario']); ?></strong>
            <span class="estrelas" title="Nota <?php echo $score; ?> de 5"><?php echo $estrelas; ?></span>
        </div>
        <p class="avaliacao-texto"><?php echo htmlspecialchars($a['descricao']); ?></p>
        <small><?php echo htmlspecialchars($a['data']); ?> &middot; clique para ler tudo</small>
    </article>
    <?php
}

/** Janela (modal) que mostra a avaliação completa. Preenchida por js/modal.js */
function render_modal_avaliacao()
{
    ?>
    <dialog id="modal-avaliacao" class="modal" data-base="<?php echo BASE_URL; ?>">
        <div class="modal-corpo">
            <button type="button" class="modal-fechar" aria-label="Fechar">&times;</button>
            <h3 data-f="receita"></h3>
            <p class="estrelas modal-estrelas" data-f="estrelas"></p>
            <p class="modal-texto" data-f="texto"></p>
            <small data-f="meta"></small>
            <a class="btn" data-f="link" href="#">Ver receita completa</a>
        </div>
    </dialog>
    <?php
}