<div>
    <label id="label_generated_password" for="generated_password">Contraseña Generada:</label>
    <div class="input-group mb-2">
        <input type="text" id="generated_password" class="form-control" placeholder="Generar Contraseña Segura"
            readonly />
        <input type="number" id="password_length" class="form-control" style="max-width: 70px" value="12"
            min="8" />
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-success" onclick="generateAndShowPassword()">
                <i class="fa-solid fa-arrows-rotate"></i>
            </button>
        </div>
    </div>

    <!-- Botón para mostrar opciones avanzadas -->
    <button class="btn btn-link p-0" type="button" data-toggle="collapse" data-target="#advancedOptions"
        aria-expanded="false" aria-controls="advancedOptions">
        Opciones Avanzadas
    </button>

    <!-- Opciones avanzadas en un collapse -->
    <div class="collapse mt-2" id="advancedOptions">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="include_uppercase" checked>
            <label class="form-check-label" for="include_uppercase">
                Incluir Mayúsculas (A-Z)
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="include_lowercase" checked>
            <label class="form-check-label" for="include_lowercase">
                Incluir Minúsculas (a-z)
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="include_numbers" checked>
            <label class="form-check-label" for="include_numbers">
                Incluir Números (0-9)
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="include_specials" checked>
            <label class="form-check-label" for="include_specials">
                Incluir Caracteres Especiales (!@#$...)
            </label>
        </div>
    </div>
</div>

@push('js')
    <script>
        const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lowercase = 'abcdefghijklmnopqrstuvwxyz';
        const numbers = '0123456789';
        const specials = '[!@#$%^&*(),.?":{}|<>]';

        const levelMessage = [
            'Fatal!',
            'Debil',
            'Moderado',
            'Bueno',
            'Fuerte'
        ];

        function generateSecurePassword(length) {
            if (length == 0) {
                alert('Longitud invalida.');
                return '';
            }

            let characters = '';

            // Verificar qué opciones están activadas
            if (document.getElementById('include_uppercase').checked) {
                characters += uppercase;
            }
            if (document.getElementById('include_lowercase').checked) {
                characters += lowercase;
            }
            if (document.getElementById('include_numbers').checked) {
                characters += numbers;
            }
            if (document.getElementById('include_specials').checked) {
                characters += specials;
            }

            // Validar que al menos haya un tipo de caracter seleccionado
            if (characters.length === 0) {
                alert('Por favor selecciona al menos una opción de caracteres.');
                return '';
            }

            const array = new Uint32Array(length);
            window.crypto.getRandomValues(array);
            return Array.from(array, x => characters[x % characters.length]).join('');
        }

        function generateAndShowPassword() {
            const password_length = $('#password_length').val();

            const password = generateSecurePassword(password_length);
            const level = evaluatePasswordStrength(password);

            const label_generated_password = $('#label_generated_password');
            label_generated_password.text(levelMessage[level]);

            $('#generated_password').val(password);
        }

        function evaluatePasswordStrength(password) {
            let level = 0;

            if (!password) {
                return level;
            }

            if (/[A-Z]/.test(password) && /[a-z]/.test(password)) level++;
            if (/\d/.test(password) || /[!@#$%^&*(),.?":{}|<>]/.test(password)) level++;

            level += Math.floor(password.length / 12);
            return Math.min(level, levelMessage.length - 1);
        }
    </script>
@endpush
