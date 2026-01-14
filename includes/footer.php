</main>
<?php require __DIR__ . '/bottom_nav.php'; ?>
<script>
/* Small UX: keep input focused on desktop with / */
document.addEventListener('keydown', (e)=>{
  if(e.key === '/' && !e.target.matches('input,textarea')){
    const el = document.querySelector('input[name="q"]');
    if(el){ e.preventDefault(); el.focus(); }
  }
});
</script>
</body>
</html>
