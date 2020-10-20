@php
    $module = null;
    $n = count($perms);
    $i = 0;
@endphp
<div class="accordion" id="accPerms">
    @foreach ($perms as $p)
        @if ($p->module !== $module)
            @php
                if ($module !== null)
                    echo "</div></div>";
            @endphp

            <div id="head_{{ $p->module }}" class="head-perms">
                <button class="btn btn-block text-left font-weight-bold"
                        type="button"
                        data-toggle="collapse" data-target="#body_{{$p->module}}" aria-expanded="true"
                        aria-controls="body_{{ $p->module }}">
                    <i class="fa fa-chevron-down mr-2"></i>
                    {{ $p->module }}
                </button>
            </div>

            @php
                $module = $p->module;
                echo "<div id='body_$module' class='collapse body-perms' aria-labelledby='head_$module' data-parent='#accPerms'><div>";
            @endphp
        @endif
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox"
                       @if(isset($selected))
                            @if($selected->contains('id', $p->id)) checked @endif
                       @endif
                       class="form-check-input"
                       name="perms[]" value="{{ $p->id }}">
                {{ $p->description }}
            </label>
        </div>
        @php
            $i++;
        @endphp
        @if ($i === $n)
            </div></div>
        @endif
    @endforeach
</div>
