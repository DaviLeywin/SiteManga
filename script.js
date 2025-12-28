const root = document.getElementById('root');
var dadosDaPagina = [];
async function CarregarHtml(documento) {
    const resposta = await fetch(`/SiteManga/Paginas/${documento}/index.html`);
    const dados = await resposta.text();
    root.innerHTML = "";
    root.innerHTML = dados;
}
function CarregarCss(documento) {
    document.querySelectorAll('link[data-pagina]').forEach(link => link.remove());
    const css = document.createElement('link')
    css.rel = "stylesheet";
    css.href = `/SiteManga/Paginas/${documento}/estilo.css`;
    css.dataset.pagina = documento;
    document.head.appendChild(css)
}
function CarregarScript(documento) {
    document.querySelectorAll('script[data-pagina]').forEach(script => script.remove());
    const js = document.createElement('script')
    js.src = `/SiteManga/Paginas/${documento}/script.js`;
    js.dataset.pagina = documento;
    document.body.appendChild(js)
}
async function CarregarPagina(valor) {
    await CarregarHtml(valor);
    CarregarCss(valor);
    CarregarScript(valor);
}
function UrlDaTelaInicial(){
    history.pushState(null,null,"/SiteManga/Inicio");
}

class Rota{
    rotas = [];
    constructor(){
        this.rotas = [
            {regex: /^\/$/, funcao: () => UrlDaTelaInicial()},
            {regex: /^\/Inicio$/,funcao: () => CarregarPagina('Inicio')},
            {regex: /^\/Manga\/[_a-zA-Z0-9-]+/,funcao: () => CarregarPagina('Manga')},
            {regex: /^\/Manga\/[_a-zA-Z0-9-\%]+\/Capitulo\/\d+$/,funcao: () => CarregarPagina('Capitulo')},
            {regex: /^\/Genero\/[\p{L}0-9-]+$/u,funcao: () => CarregarPagina('Genero')}
        ]
    }
    async Executar(){
        const url = window.location.pathname.replace('/SiteManga','');//nao ta funcionando com essa url /Genero/Com%C3%A9dia que era comedia ou e outra coisa e n sei
        const url2 = decodeURIComponent(url);
        for(const rota of this.rotas){
            if(rota.regex.test(url2)){
                await rota.funcao();
                return;
            }
        }
        history.pushState(null,null,"/SiteManga/Inicio");
        await CarregarPagina('Inicio');
    }
}

const router = new Rota();
window.addEventListener('load', async () => {router.Executar();})
window.addEventListener('popstate', async () => {router.Executar();})