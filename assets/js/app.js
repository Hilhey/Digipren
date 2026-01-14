(function(){
  // Simple banner carousel
  const carousels = document.querySelectorAll('[data-carousel]');
  carousels.forEach((wrap)=>{
    const track = wrap.querySelector('.carousel-track');
    const dots = wrap.querySelectorAll('[data-dot]');
    if(!track) return;
    let idx = 0;
    const slides = track.children.length;
    const go = (i)=>{
      idx = (i + slides) % slides;
      track.style.transform = `translateX(${-idx*100}%)`;
      dots.forEach((d,k)=>d.classList.toggle('active', k===idx));
    };
    wrap.querySelectorAll('[data-prev]').forEach(b=>b.addEventListener('click', ()=>go(idx-1)));
    wrap.querySelectorAll('[data-next]').forEach(b=>b.addEventListener('click', ()=>go(idx+1)));
    dots.forEach((d,k)=>d.addEventListener('click', ()=>go(k)));
    let t = setInterval(()=>go(idx+1), 4500);
    wrap.addEventListener('mouseenter', ()=>{ clearInterval(t); });
    wrap.addEventListener('mouseleave', ()=>{ t = setInterval(()=>go(idx+1), 4500); });
    go(0);
  });

  // Product gallery switch
  document.querySelectorAll('[data-gallery]').forEach((g)=>{
    const main = g.querySelector('[data-main]');
    if(!main) return;
    g.querySelectorAll('[data-thumb]').forEach((t)=>{
      t.addEventListener('click', ()=>{
        const src = t.getAttribute('data-src');
        if(src) main.setAttribute('src', src);
        g.querySelectorAll('[data-thumb]').forEach(x=>x.classList.remove('active'));
        t.classList.add('active');
      });
    });
  });
})();
