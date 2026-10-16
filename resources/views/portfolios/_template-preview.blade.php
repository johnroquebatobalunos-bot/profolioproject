@if ($slug === 'simple')
    <div class="mini-simple">
        <div class="mini-preview-top"><span>JORDAN LEE</span><span>01 / 03</span></div>
        <div class="mini-simple-main"><div><small>DESIGNER / BROOKLYN</small><b>Thoughtful<br>by design.</b><span>Selected work <i>↗</i></span></div><img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&amp;fit=crop&amp;w=260&amp;q=80" alt=""></div>
        <div class="mini-simple-work"><span>01 BRAND</span><span>02 DIGITAL</span><span>03 EDITORIAL</span></div>
    </div>
@elseif ($slug === 'modern')
    <div class="mini-modern">
        <div class="mini-preview-top"><span>PF / JORDAN LEE</span><span>PORTFOLIO — {{ date('Y') }}</span></div>
        <div class="mini-modern-main"><img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&amp;fit=crop&amp;w=280&amp;q=80" alt=""><div><small>INDEPENDENT CREATIVE</small><b>Make good<br>things happen.</b></div></div>
        <div class="mini-modern-work"><span>SELECTED WORK</span><i></i><i></i><i></i></div>
    </div>
@else
    <div class="mini-creative">
        <div class="mini-preview-top"><span>MAKE / MORE</span><span>© {{ date('Y') }}</span></div>
        <div class="mini-creative-main"><b>MAKE<br>WAVES</b><img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&amp;fit=crop&amp;w=250&amp;q=80" alt=""><i></i></div>
        <div class="mini-creative-work"><span>IDEAS IN MOTION</span><span>↗ PROJECTS</span></div>
    </div>
@endif