const root = document.getElementById('root');

async function CarregarHtml(documento) {
    const resposta = await fetch(`Paginas/${documento}/index.html`);
    const dados = await resposta.text();
    root.innerHTML = dados;
    return;
}
function CarregarCss(documento) {
    document.querySelectorAll('link[data-pagina]').forEach(link => link.remove());
    const css = document.createElement('link')
    css.rel = "stylesheet";
    css.href = `Paginas/${documento}/estilo.css`;
    css.dataset.pagina = documento;
    document.head.appendChild(css)
}
function CarregarScript(documento) {
    document.querySelectorAll('script[data-pagina]').forEach(script => script.remove());
    const js = document.createElement('script')
    js.src = `Paginas/${documento}/script.js`;
    js.dataset.pagina = documento;
    document.body.appendChild(js)
}
async function Rotas(){
    if(!root)return;
    const url = window.location.pathname;
    const partes = url.split('/');
    console.log(partes[2])

    switch (partes[2]){
        case 'index.html':
            history.pushState(null,null,"/SiteManga/Inicio")
            break
        case 'Inicio':
            await CarregarHtml('Inicio');
            CarregarCss('Inicio');
            CarregarScript('Inicio');
            break
        case 'Manga':
            // await CarregarHtml('Manga');
            // CarregarCss('Manga');
            // CarregarScript('Manga');
            break
        case 'oi':
            break
    }

}
window.addEventListener('load',Rotas)
window.addEventListener('popstate',Rotas)