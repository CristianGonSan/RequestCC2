class LivewireSelect2Builder {

    constructor(wireInstance = null) {
        this.select2Config = {
            allowClear: true,
            theme: 'bootstrap4',
            language: 'es',
            width: '100%',
            placeholder: 'Seleccionar...',
            minimumInputLength: 0,
            dropdownAutoWidth: true,
        };

        this.internal = {
            $wire: wireInstance,
            selector: null,
            wireModel: null,
            live: false,
            wireEventName: 'reset',
            minExplicit: false,
        };
        this.valueOption = { id: null, text: null };
    }

    wire($wire)            { this.internal.$wire = $wire; return this; }
    selector(selector)     { this.internal.selector = selector; return this; }
    wireModel(model)       { this.internal.wireModel = model; return this; }
    live(live = true)      { this.internal.live = live; return this; }
    wireReset(eventName)   { this.internal.wireEventName = eventName; return this; }
    value(id, text)        { this.valueOption = { id, text }; return this; }
    placeholder(text)      { this.select2Config.placeholder = text; return this; }
    appendConfig(config)   { Object.assign(this.select2Config, config); return this; }
    allowClear()           { this.select2Config.allowClear = true; return this; }
    noAllowClear()         { this.select2Config.allowClear = false; return this; }

    minInpLength(n = 1) {
        this.select2Config.minimumInputLength = n;
        this.internal.minExplicit = true;
        return this;
    }

    noMinInpLength() {
        this.select2Config.minimumInputLength = 0;
        this.internal.minExplicit = true;
        return this;
    }

    build() {
        const { $wire, selector, wireModel, live, wireEventName, minExplicit } = this.internal;
        const { id, text } = this.valueOption;

        if (!$wire || !selector) {
            throw new Error('LivewireSelect2Builder: configuración incompleta');
        }

        const $el = $(selector);
        if (!$el.length) {
            throw new Error(`LivewireSelect2Builder: no existe "${selector}"`);
        }

        // Solo forzar mínimo 1 con ajax si no se definió explícitamente
        const config = { ...this.select2Config };
        if (config.ajax && !minExplicit && config.minimumInputLength === 0) {
            config.minimumInputLength = 1;
        }

        // Evitar doble inicialización y listeners duplicados
        LivewireSelect2Builder.destroy($el);

        $el.select2(config);

        // Valor inicial sin disparar nuestro handler
        if (id != null && text != null) {
            $el.append(new Option(text, id, true, true)).trigger('change.select2');
        }

        if (wireModel) {
            $el.on('change.lwSelect2', function () {
                $wire.set(wireModel, $(this).val(), live);
            });
        }

        if (wireEventName) {
            const off = Livewire.on(wireEventName, () => {
                $el.val(null).trigger('change.select2'); // solo refresca la UI
            });
            $el.data('lwSelect2Off', off);
        }

        return $el;
    }

    static destroy($el) {
        $el = $($el);
        const off = $el.data('lwSelect2Off');
        if (typeof off === 'function') off();
        $el.removeData('lwSelect2Off').off('.lwSelect2');
        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }
    }
}
