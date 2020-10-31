<div class="sidebar bg-dark">
    <ul class="list-unstyled">
        <li><a href="{{ route('data') }}"><i class="fas fa-fw fa-file-code"></i> Datos</a></li>
        <li>
            <a href="#submenu1" data-toggle="collapse"><i class="fas fa-fw fa-tags"></i> Clasificación</a>
            <ul id="submenu1" class="list-unstyled collapse">
                <li><a href="{{ route('clasificacion') }}">Detector Bot</a></li>
                <li><a href="{{ route('botometer') }}">Botometer</a></li>
            </ul>
        </li>
    </ul>
</div>