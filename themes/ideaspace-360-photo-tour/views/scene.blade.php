@extends('theme::index')

@section('title', $space_title)

@section('scene')

    <a-scene isvr-scene>

    @include('theme::assets')

        <a-entity position="0 0 0">
            <a-entity
                id="camera" 
                camera="far: 10000; fov: 80; near: 0.1; userHeight: 1.6"
                look-controls>
                <a-entity
                    cursor="fuse: false; rayOrigin: mouse"
                    raycaster="far:5001" 
                    id="cursor"
                    position="0 0 -1.9"
                    geometry="primitive: circle; radius: 0.02;"
                    material="color: #FFFFFF; shader: flat;"
                    visible="false">
                </a-entity>
            </a-entity>
        </a-entity>

        <a-entity laser-controls="hand: left" raycaster="far:5001" line="color: #FFFFFF" class="laser-controls"></a-entity>
        <a-entity laser-controls="hand: right" raycaster="far:5001" line="color: #FFFFFF" class="laser-controls"></a-entity>


        @if (isset($content['photo-spheres']) && count($content['photo-spheres']) > 0) 

            <a-entity
                isvr-init-assets="url: {{ $space_url }}/content/photo-spheres"
                geometry="primitive: sphere; radius: 5000; segmentsWidth: 64; segmentsHeight: 64"
                material="shader: flat; side: double; color: #FFFFFF"
                scale="-1 1 1"
                rotation="0 -90 0" id="photosphere">
                <a-animation
                    attribute="material.color"
                    begin="photosphere-fade-out"
                    dur="500"
                    from="#FFFFFF"
                    to="#000000">
                </a-animation>
                <a-animation
                    attribute="material.color"
                    begin="photosphere-fade-in"
                    dur="500"
                    from="#000000"
                    to="#FFFFFF">
                </a-animation>
            </a-entity>

            <a-light type="ambient" color="#FFFFFF"></a-light>


            @foreach ($content['photo-spheres'] as $photosphere)

                @if (isset($photosphere['photosphere-references']))
                    @foreach ($photosphere['photosphere-references']['#positions'] as $photosphere_reference)

                        <a-entity 
                            rotation="{{ $photosphere_reference['#rotation']['#x'] }} {{ $photosphere_reference['#rotation']['#y'] }} {{ $photosphere_reference['#rotation']['#z'] }}">
                            <a-circle  
                                class="hotspot-navigation hotspot-navigation-content-id-{{ $photosphere['photo-sphere']['#content-id'] }}" 
                                data-content-id="{{ $photosphere_reference['#content-id'] }}" 
                                isvr-hotspot-navigation-wrapper-listener
                                material="transparent: false; opacity: 0"
                                position="0 1.6 -2.1"   
                                radius="0.4" 
                                scale="0.7 0.7 0.7" 
                                visible="false">
                                <a-ring
                                    color="{{ $photosphere['navigation-hotspot-color']['#value'] }}"
                                    position="0 0 0"
                                    radius-inner="0.11"
                                    radius-outer="0.13">
                                    <a-circle
                                        color="{{ $photosphere['navigation-hotspot-color']['#value'] }}"
                                        position="0 0 0.01"
                                        radius="0.09">
                                        <a-animation
                                            attribute="geometry.radius"
                                            to="0.08"
                                            dur="1500"
                                            direction="alternate"
                                            repeat="indefinite"
                                            easing="linear">
                                        </a-animation>
                                        <a-plane 
                                            class="hotspot-navigation-arrow hotspot-navigation-arrow-3-content-id-{{ $photosphere['photo-sphere']['#content-id'] }}"
                                            visible="true" 
                                            position="-0.011 0 0.03" 
                                            rotation="0 0 -45" 
                                            color="{{ $photosphere['navigation-hotspot-arrow-color']['#value'] }}"
                                            width="0.03" 
                                            height="0.06">
                                        </a-plane>
                                        <a-circle
                                            color="{{ $photosphere['navigation-hotspot-arrow-color']['#value'] }}"
                                            position="-0.032 -0.021 0.03"
                                            rotation="0 0 -225"
                                            theta-length="180"
                                            radius="0.015">
                                        </a-circle>
                                        <a-plane 
                                            class="hotspot-navigation-arrow hotspot-navigation-arrow-3-content-id-{{ $photosphere['photo-sphere']['#content-id'] }}"
                                            visible="true" 
                                            position="0.011 0 0.031" 
                                            rotation="0 0 45" 
                                            color="{{ $photosphere['navigation-hotspot-arrow-color']['#value'] }}"
                                            width="0.03" 
                                            height="0.06">
                                        </a-plane>
                                        <a-circle
                                            color="{{ $photosphere['navigation-hotspot-arrow-color']['#value'] }}"
                                            position="0.032 -0.021 0.03"
                                            rotation="0 0 -135"
                                            theta-length="180"
                                            radius="0.015">
                                        </a-circle>
                                    </a-circle>
                                </a-ring>

                                @if (trim($photosphere_reference['#content']['title']['#value']) != '')

                                    @if ($photosphere_reference['#content']['text-styling']['#value'] == 'text-boxes') 

                                        @include('theme::partials.photosphere_title_box_partial')

                                    @elseif ($photosphere_reference['#content']['text-styling']['#value'] == 'floating-text') 

                                        @include('theme::partials.photosphere_floating_title_partial')

                                    @endif

                                @endif

                            </a-circle>
                        </a-entity>

                    @endforeach
                @endif



                @if (isset($photosphere['hotspot-annotations']))
                    @foreach ($photosphere['hotspot-annotations']['#positions'] as $annotation)

                        @php 
                        $rand = str_random();
                        @endphp

                        <a-entity 
                            rotation="{{ $annotation['#rotation']['#x'] }} {{ $annotation['#rotation']['#y'] }} {{ $annotation['#rotation']['#z'] }}">
                            <a-circle 
                                class="hotspot hotspot-content-id-{{ $photosphere['hotspot-annotations']['#content-id'] }}" 
                                data-content-id="{{ $photosphere['hotspot-annotations']['#content-id'] }}" 
                                data-text-content-id="{{ $annotation['#content-id'] . $rand }}"
                                isvr-hotspot-wrapper-listener
                                material="transparent: false; opacity: 0"
                                position="0 1.6 -2.1" 
                                radius="0.4" 
                                scale="0.7 0.7 0.7" 
                                visible="false">
                                <a-ring
                                    color="{{ $annotation['#content']['hotspot-color']['#value'] }}" 
                                    position="0 0 0"
                                    radius-inner="0.11"
                                    radius-outer="0.13">
                                    <a-circle 
                                        color="{{ $annotation['#content']['hotspot-color']['#value'] }}" 
                                        radius="0.09" 
                                        position="0 0 0.01"> 
                                        <a-animation
                                            attribute="geometry.radius"
                                            to="0.08"
                                            dur="1500"
                                            direction="alternate"
                                            repeat="indefinite"
                                            easing="linear">
                                        </a-animation>
                                        <a-circle 
                                            color="#FFF" 
                                            radius="0.02" 
                                            position="0 0 0.02"> 
                                        </a-circle>
                                    </a-circle>
                                </a-ring>
                            </a-circle>

                            <!-- hotspot text //-->
                            @if ($photosphere['text-styling']['#value'] == 'text-boxes')

                                @include('theme::partials.hotspot_text_box_partial')

                            @elseif ($photosphere['text-styling']['#value'] == 'floating-text')

                                @include('theme::partials.hotspot_floating_text_partial')

                            @endif
                            <!-- hotspot text //-->

                        </a-entity>

                    @endforeach

                @endif

            @endforeach

        @endif


        <a-entity
            id="intro-0"
            visible="true"
            position="0 1.6 -2.1"
            geometry="primitive: plane; width: 1.8; height: 1"
            material="color: #FFFFFF; transparent: true; opacity: 0.5">
            <a-entity
                geometry="primitive: plane; width: 1.74; height: 0.94"
                position="0 0 0.01"
                material="color: #606060">
                <a-entity 
                    geometry="primitive: plane; width: 1.6; height: 0.4"
                    position="0 0.2 0.02" 
                    material="color: #606060">
                    <a-text
                        value="{{ $space_title }}"
                        color="#FFFFFF"
                        anchor="center"
                        width="1.6">
                    </a-text>
                </a-entity>
                <a-ring 
                    position="0 -0.2 0.03" 
                    color="#000000" 
                    radius-inner="0.108" 
                    radius-outer="0.145" 
                    id="photosphere-loading-background">
                </a-ring>
                <a-ring 
                    position="0 -0.2 0.04" 
                    color="#0080e5" 
                    radius-inner="0.105" 
                    radius-outer="0.145" 
                    theta-length="120" 
                    id="photosphere-loading">
                    <a-animation
                        attribute="rotation"
                        dur="1000"
                        to="0 0 -360"
                        easing="linear"
                        repeat="indefinite"
                        id="photosphere-loading-anim">
                    </a-animation>
                </a-ring>

                <a-entity 
                    id="photosphere-start-btn"
                    visible="false">
                    <a-circle
                        position="0 -0.2 0.03" 
                        radius="0.125" 
                        color="#0080e5">
                        <a-animation
                            attribute="geometry.radius"
                            to="0.140"
                            dur="1300"
                            direction="alternate"
                            repeat="indefinite"
                            easing="linear">
                        </a-animation>
                    </a-circle>
                    <a-text
                        position="0.717 -0.194 0.04" 
                        value="Start"
                        color="#FFFFFF"
                        anchor="center"
                        width="1.6">
                    </a-text>
                </a-entity>
            </a-entity>
        </a-entity><!-- intro-0 //-->

        <a-entity
            id="no-hmd-intro"
            visible="false"
            position="0 0.85 -2.1"
            geometry="primitive: plane; width: 1.8; height: 0.46"
            material="color: #FFFFFF; transparent: true; opacity: 0.5">
            <a-entity
                geometry="primitive: plane; width: 1.74; height: 0.4"
                position="0 0 0.01"
                material="color: #606060">
                <a-entity 
                    geometry="primitive: plane; width: 1.6; height: 0.35"
                    position="0 0.01 0.02" 
                    material="color: #606060">
                    <a-text
                        value="No headset connected. Click and drag to look around and click to select items."
                        color="#FFFFFF"
                        anchor="center"
                        width="1.6">
                    </a-text>
                </a-entity>
            </a-entity>
        </a-entity><!-- no-hmd-intro //-->

    </a-scene>
@endsection
