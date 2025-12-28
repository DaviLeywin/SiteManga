(async function(){

const state = history.state;
async function PegarMangasDoGenero() {
    if(!state || !state.generoId){
        return;
    }
    const id = state.generoId;
    resposta = await fetch(`/SiteManga/api/GetMangaPorId/${id}`,{
        method:'GET'
    })
    const dados = await resposta.json();
    if(!dados.sucesso)return;
    MontarMangas(dados.resposta);
}
function MontarMangas(mangas){
    console.log(mangas)
    mangas.forEach(manga => {
        const mangasHtml = document.getElementById('mangas');
        const generoHtml = document.getElementById('genero');
        generoHtml.innerText = state.genero

        const mangaHtml = document.createElement('div');
        const capaHtml = document.createElement('div');
        const imagenCapaHtml = document.createElement('img');
        const dadosDoMangaHtml = document.createElement('div');
        const nomeDoMangaHtml = document.createElement('span');
        const statusDoMangaHtml = document.createElement('span');
        
        mangaHtml.classList.add('manga');
        capaHtml.classList.add('capa');
        imagenCapaHtml.classList.add('imagem-da-capa');
        dadosDoMangaHtml.classList.add('dados-do-manga');
        nomeDoMangaHtml.classList.add('nome-do-manga');
        statusDoMangaHtml.classList.add('status-do-manga')

        mangasHtml.appendChild(mangaHtml);
        mangaHtml.appendChild(capaHtml);
        mangaHtml.appendChild(dadosDoMangaHtml);
        capaHtml.appendChild(imagenCapaHtml);
        dadosDoMangaHtml.appendChild(nomeDoMangaHtml);
        dadosDoMangaHtml.appendChild(statusDoMangaHtml);

        imagenCapaHtml.src = "/SiteManga/"+manga.CAPA_URL
        nomeDoMangaHtml.innerText = manga.TITULO
        statusDoMangaHtml.innerText = manga.STATUS

        mangaHtml.addEventListener('click', () => {
            const nomeManga = manga.TITULO.replaceAll(" ","-")
            history.pushState({id:manga.ID , nomeFormatado:nomeManga}, null, `/SiteManga/Manga/${nomeManga}`);
            router.Executar();
        });
        
    })
}
PegarMangasDoGenero()
})()