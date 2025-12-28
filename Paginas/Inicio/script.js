(async function(){
const mangas = document.getElementById('mangas');

async function fetchMangas() {
    resposta = await fetch("/SiteManga/api/GetMangasGeneros",{
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
            const CapaManga = document.createElement('img');
            const TituloManga = document.createElement('h3');
            
            ContainerManga.setAttribute('data-id-manga', manga.ID);
            
            ContainerManga.classList.add('manga');
            CapaManga.classList.add('capa-manga');
            TituloManga.classList.add('titulo-manga');
            if(manga.CAPA_URL !== null){
                CapaManga.src = manga.CAPA_URL;
                CapaManga.alt = '#';
            }else if(manga.CAPA_URL == null){
                CapaManga.src = "CAPA_URL";
                CapaManga.alt = '#';
            }
            TituloManga.innerText = manga.TITULO;
            const texto = manga.TITULO;
            const tamanho = texto.length;
            if(tamanho > 26){
                const valor = manga.TITULO.substr(0,23)+"...";
                TituloManga.innerText = valor;
            }

            ContainerManga.appendChild(CapaManga);
            ContainerManga.appendChild(TituloManga);
            manga.GENEROS.forEach(genero => {
                const containerGenero = document.querySelector(`.carousel[data-id="${genero.ID}"]`);
                if(!containerGenero)return;
                const clone = ContainerManga.cloneNode(true);
                containerGenero.appendChild(clone);
                clone.addEventListener('click', () => {
                    const nomeManga = manga.TITULO.replaceAll(" ","-")
                    dadosDaPagina.ID = manga.ID
                    dadosDaPagina.TITULO = manga.TITULO
                    history.pushState({id:manga.ID , nomeFormatado:nomeManga}, null, `/SiteManga/Manga/${nomeManga}`);
                    router.Executar();
                });
            }) 
        });
    }
}
async function fetchGeneros() {
    resposta = await fetch("/SiteManga/api/GetAllGeneros",{
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