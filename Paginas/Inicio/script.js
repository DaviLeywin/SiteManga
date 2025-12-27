(async function(){
const mangas = document.getElementById('mangas');

async function fetchMangas() {
    resposta = await fetch("api/GetMangasGeneros",{
        method: 'GET',
    })
    const MangasDados = await resposta.json();
    if(!MangasDados.sucesso){
        alert("Erro ao carregar mangas");
        return;
    }
    else if(MangasDados.sucesso){
        MangasDados.resposta.forEach( manga => {
            const ContainerManga = document.createElement('div');
            const CapaManga = document.createElement('div');
            const TituloManga = document.createElement('h3');
            
            ContainerManga.setAttribute('data-id-manga', manga.ID);
            
            ContainerManga.classList.add('manga');
            CapaManga.classList.add('capa-manga');
            TituloManga.classList.add('titulo-manga');
            
            TituloManga.innerText = manga.TITULO;
            ContainerManga.appendChild(CapaManga);
            ContainerManga.appendChild(TituloManga);
            manga.GENEROS.forEach(genero => {
                const containerGenero = document.querySelector(`.carousel[data-id="${genero.ID}"]`);
                if(!containerGenero)return;
                const clone = ContainerManga.cloneNode(true);
                containerGenero.appendChild(clone);
                clone.addEventListener('click', () => {
                    history.pushState(null, null, `/SiteManga/Manga/${manga.ID}`);
                    Rotas();
                });
            }) 
        });
    }
}
async function fetchGeneros() {
    resposta = await fetch("api/GetAllGeneros",{
        method: 'GET',
    })
    const Generos = await resposta.json();
    Generos.resposta.forEach(genero =>{
        const MangasDoGenero = document.createElement('div');
        const NomeGenero = document.createElement('h2');
        const carouselWrapper = document.createElement('div');
        const btnEsq = document.createElement('button');
        const btnDir = document.createElement('button');
        const MangasGenero = document.createElement('div');

        MangasDoGenero.classList.add('genero');
        NomeGenero.classList.add('titulo-genero');
        carouselWrapper.classList.add('carousel-wrapper');
        MangasGenero.classList.add('carousel');

        btnEsq.classList.add('seta', 'esquerda');
        btnDir.classList.add('seta', 'direita');
        btnEsq.innerText = '◀';
        btnDir.innerText = '▶';

        MangasGenero.setAttribute('data-id', genero.ID);

        carouselWrapper.appendChild(btnEsq);
        carouselWrapper.appendChild(MangasGenero);
        carouselWrapper.appendChild(btnDir);

        MangasDoGenero.appendChild(NomeGenero);
        MangasDoGenero.appendChild(carouselWrapper);
        mangas.appendChild(MangasDoGenero);

        NomeGenero.innerText = genero.NOME;

        // movimento
        const passo = 250;

        btnDir.addEventListener('click', () => {
            MangasGenero.scrollLeft += passo;
        });

        btnEsq.addEventListener('click', () => {
            MangasGenero.scrollLeft -= passo;
        });
    })
}

await fetchGeneros();
await fetchMangas();
})()