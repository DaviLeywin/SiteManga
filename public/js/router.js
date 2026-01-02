import { RenderPageHome } from '/SiteLivros/public/js/pages/home.js'
import { RenderPageManga } from '/SiteLivros/public/js/pages/manga.js'
import { RenderPageGender } from './pages/gender.js'
// import { RenderPageChapter } from './pages/chapter.js'

async function MudarHtml(documento){
    const root = document.getElementById('root');
    const resposta = await fetch(`/SiteLivros/public/pages/${documento}.html`);
    const dados = await resposta.text();
    root.innerHTML = '';
    root.innerHTML = dados;
}

function CarregarPaginas(Pagina, parametro = false){
    const relacoes = [
        {pagina:'home', funcao: (valor) => RenderPageHome(valor)},
        {pagina:'gender', funcao: (valor) => RenderPageGender(valor)},
        {pagina:'chapter', funcao: (valor) => RenderPageChapter(valor)},
        {pagina:'manga', funcao: (valor) => RenderPageManga(valor)},
    ];
    for(const relacao of relacoes){
        if(relacao.pagina == Pagina){
            MudarHtml(Pagina);
            relacao.funcao(parametro);
            return;
        }
    }
}
class Rotas {
    rotas = []
    constructor(){
        this.rotas = [
            {regex: /^\/Inicio$/,funcao: (tipo) => MudarHtml(tipo), tipo:'home'},
            {regex: /^\/Manga\/([^<>"'\\|?*\[\]{}]+)$/,funcao: (tipo) => MudarHtml(tipo), tipo:'manga'},
            {regex: /^\/Manga\/[_a-zA-Z0-9-\%]+\/Capitulo\/\d+$/,funcao: (tipo) => MudarHtml(tipo), tipo:'chapter'},
            {regex: /^\/Genero\/([^<>"'\\|?*\[\]{}]+)$/u,funcao: (tipo) => MudarHtml(tipo), tipo:'gender'}
        ]
    }
    async Execute(){
        const url = window.location.pathname.replace('/SiteLivros','');
        const urlFormatada = decodeURIComponent(url);
        for(const rota of this.rotas){
            if(rota.regex.test(urlFormatada)){
                await rota.funcao(rota.tipo);
                const parametro = urlFormatada.match(rota.regex)
                CarregarPaginas(rota.tipo, parametro[1]);
                return;
            }
        }
        alert("Pagina nao encontrada!")
        history.pushState(null,null,"/SiteLivros/Inicio");
        CarregarPaginas('home')
    }
}

export const router = new Rotas();
window.addEventListener('popstate',async () => {router.Execute()});
window.addEventListener('load',async () => {router.Execute()});