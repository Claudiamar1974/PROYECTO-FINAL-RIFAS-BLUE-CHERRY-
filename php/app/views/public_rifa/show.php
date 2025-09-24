<?php $title = htmlspecialchars($rifa['titulo']); require_once __DIR__ . '/../../../config/app.php'; include __DIR__ . '/../partials/header.php'; ?>

    <style>
        .numero-rifa { display:inline-block; width:48px; height:48px; margin:2px; text-align:center; line-height:48px; border-radius:8px; font-weight:bold; cursor:pointer; border:2px solid #ccc; background:#fff; transition:all .2s; }
        .numero-libre { border-color:#00c853; color:#00c853; background:#e8f5e9; }
        .numero-reservado { border-color:#ff9800; color:#ff9800; background:#fff3e0; text-decoration:line-through; cursor:not-allowed; }
        .numero-vendido { border-color:#d32f2f; color:#d32f2f; background:#ffebee; text-decoration:line-through; cursor:not-allowed; }
        .numero-seleccionado { border-color:#1976d2; color:#fff; background:#1976d2; }
        #popup-numeros { display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.2); padding:24px; z-index:1001; max-width:90vw; width:400px; }
        #fondo-popup { display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:1000; }
        #contador-expira { color:#1976d2; font-weight:bold; margin-bottom:10px; }
        @media (max-width:600px) { #popup-numeros { width:98vw; padding:10px; } .numero-rifa { width:36px; height:36px; line-height:36px; font-size:15px; } }
    </style>

    <h1><?= htmlspecialchars($rifa['titulo']) ?></h1>
    <?php if (!empty($rifa['imagen'])): ?>
        <div class="mb-3">
            <img src="/public/img/rifas/<?= htmlspecialchars($rifa['imagen']) ?>" alt="<?= htmlspecialchars($rifa['titulo']) ?>" style="max-width:100%;height:auto;" />
        </div>
    <?php endif; ?>
    <p><?= htmlspecialchars($rifa['descripcion']) ?></p>
    <p>Precio por número: $<?= $rifa['precio_por_numero'] ?></p>
    <?php if ($rifa['id_ganador']): ?>
        <div class="alert alert-info" role="alert">
            🎉 Ganador: <?php
                $stmt = $pdo->prepare('SELECT numero FROM numeros_rifa WHERE id=?');
                $stmt->execute([$rifa['id_ganador']]);
                $num = $stmt->fetchColumn();
                echo $num ? $num : 'No disponible';
            ?>
        </div>
    <?php endif; ?>
    <button id="abrir-popup" class="btn btn-primary">Seleccionar números</button>
    <form id="form-reserva" method="post" action="index.php?r=publicrifa/reservar&id=<?= $rifa['id'] ?>">
        <input type="hidden" name="numeros" id="input-numeros">
    </form>
    <div id="fondo-popup"></div>
    <div id="popup-numeros">
    <h3>Selecciona tus números (máx 10)</h3>
        <div id="numeros-container" style="display:flex;flex-wrap:wrap;justify-content:center;max-width:360px;margin:auto;">
        <?php foreach($numeros as $num):
            $clase = $num['estado']=='libre' ? 'numero-libre' : ($num['estado']=='reservado' ? 'numero-reservado' : 'numero-vendido');
            if ($rifa['id_ganador'] && $num['id']==$rifa['id_ganador']) $clase .= ' numero-seleccionado';
        ?>
            <div class="numero-rifa <?= $clase ?>" data-id="<?= $num['id'] ?>" data-estado="<?= $num['estado'] ?>">
                <?= $num['numero'] ?>
            </div>
        <?php endforeach; ?>
        </div>
        <div id="mensaje-popup" style="color:#d32f2f; margin:10px 0; min-height:20px;"></div>
        <div class="d-flex">
          <button id="confirmar-numeros" class="btn btn-success me-2" style="margin-top:10px;">Confirmar selección</button>
          <button id="cerrar-popup" type="button" class="btn btn-secondary" style="margin-top:10px;">Cerrar</button>
        </div>
    </div>
    <a class="btn btn-link" href="index.php?r=publicrifa">Volver</a>
    <script>
    const popup = document.getElementById('popup-numeros');
    const fondo = document.getElementById('fondo-popup');
    const abrir = document.getElementById('abrir-popup');
    const cerrar = document.getElementById('cerrar-popup');
    const confirmar = document.getElementById('confirmar-numeros');
    const numeros = document.querySelectorAll('.numero-rifa');
    const inputNumeros = document.getElementById('input-numeros');
    const mensaje = document.getElementById('mensaje-popup');
    let seleccionados = [];

    abrir.onclick = () => { 
        popup.style.display='block'; fondo.style.display='block'; 
    };
    cerrar.onclick = () => { 
        popup.style.display='none'; fondo.style.display='none'; mensaje.textContent=''; seleccionados=[]; numeros.forEach(n=>n.classList.remove('numero-seleccionado'));
    };
    fondo.onclick = cerrar.onclick;
    numeros.forEach(n => {
        if (n.dataset.estado==='libre') {
            n.onclick = () => {
                const id = n.dataset.id;
                if (seleccionados.includes(id)) {
                    seleccionados = seleccionados.filter(x=>x!==id);
                    n.classList.remove('numero-seleccionado');
                } else {
                    if (seleccionados.length>=10) {
                        mensaje.textContent = 'Solo puedes seleccionar hasta 10 números.';
                        return;
                    }
                    seleccionados.push(id);
                    n.classList.add('numero-seleccionado');
                }
                mensaje.textContent = '';
            };
        }
    });
    confirmar.onclick = (e) => {
        e.preventDefault();
        if (seleccionados.length===0) {
            mensaje.textContent = 'Selecciona al menos un número.';
            return;
        }
        inputNumeros.value = JSON.stringify(seleccionados);
        document.getElementById('form-reserva').submit();
    };
    </script>

<?php include __DIR__ . '/../partials/footer.php'; ?>