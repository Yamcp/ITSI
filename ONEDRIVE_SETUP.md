# Guía: Configurar OneDrive para Backups Automáticos

## Paso 1: Registrar la Aplicación en Azure AD

1. Dirígete a [Azure Portal](https://portal.azure.com)
2. Busca **Enregistrements d'applications** (App registrations)
3. Haz clic en **Nouvelle inscription** (New registration)
4. Completa:
   - **Nom**: "ITSI Backup Manager"
   - **Comptes pris en charge**: "Comptes dans ce répertoire organisationnel uniquement"
5. Haz clic en **Inscrire** (Register)

## Paso 2: Obtener Credenciales

En la página de registro de la aplicación:

1. Copia el **ID de l'application (client)** → este es tu `CLIENT_ID`
2. Copia el **ID du répertoire (tenant)** → este es tu `TENANT_ID`
3. Ve a **Certificats et secrets**:
   - Haz clic en **Nouveau secret client**
   - Dale una descripción: "Backup Application"
   - Copia el valor del secreto → este es tu `CLIENT_SECRET`

## Paso 3: Configurar el URI de Redireccionamiento

1. Ve a **Authentification**
2. Haz clic en **Ajouter une plateforme** (Add a platform)
3. Selecciona **Application Web**
4. En **URI de redirection**, agrega:
   ```
   https://tusitio.com/admin/onedrive/callback
   ```
   (Reemplaza `tusitio.com` con tu dominio real)

5. Marca las casillas:
   - ✅ **Jetons d'accès** (Access tokens)
   - ✅ **Jetons ID** (ID tokens)

## Paso 4: Configurar Permisos

1. Ve a **Autorisations de l'API**
2. Haz clic en **Ajouter une autorisation** (Add a permission)
3. Selecciona **Microsoft Graph**
4. Selecciona **Autorisations déléguées** (Delegated permissions)
5. Busca y agrega:
   - ✅ `Files.ReadWrite.All`
   - ✅ `offline_access`

6. Haz clic en **Accorder le consentement administrateur**

## Paso 5: Completar la Configuración en ITSI

1. Abre `app/Controllers/admin/OneDriveController.php`
2. Reemplaza estas líneas con tus valores de Azure:

```php
private $clientId = 'AQUÍ_TU_CLIENT_ID';
private $clientSecret = 'AQUÍ_TU_CLIENT_SECRET';
private $tenantId = 'AQUÍ_TU_TENANT_ID';
```

Ejemplo:
```php
private $clientId = '12345678-1234-1234-1234-123456789012';
private $clientSecret = 'abc~xyz123456789.defghijk-_-LMNOPQRST';
private $tenantId = 'a1b2c3d4-a1b2-c3d4-a1b2-c3d4a1b2c3d4';
```

## Paso 6: Probar la Conexión

1. Ve a la página de **Backup** en la admin panel
2. En el modal **Backup Automático**:
   - Selecciona **Tipo de almacenamiento**: "Remoto"
   - Aparecerá el botón "Conectar con OneDrive"
   - Haz clic en el botón para autorizar
3. Microsoft te pedirá que inicies sesión
4. Después del login, volverás automáticamente a ITSI
5. Si todo es correcto, verás "✓ Conectado a OneDrive"

## Paso 7: Usar OneDrive para Backups

Una vez conectado:
1. Los backups automáticos se guardarán en tu OneDrive
2. Se crearán en la carpeta `/Me/drive/root:/Backups/`
3. Los archivos se nombrarán automáticamente con la fecha y hora

## Troubleshooting

### Error: "Código de autorización no recibido"
- Verifica que el **URI de redirection** en Azure coincida exactamente con:
  `https://tusitio.com/admin/onedrive/callback`

### Error: "No se pudo obtener el token de acceso"
- Revisa que `CLIENT_SECRET` sea correcto (incluye guiones)
- Verifica que los permisos estén asignados en Azure

### Error: "Error al subir archivo"
- Revisa los logs del servidor
- Verifica que el token no haya expirado
- Intenta desconectar y conectar nuevamente

## Notas de Seguridad

⚠️ **IMPORTANTE**:
- NUNCA compartas tus valores `CLIENT_ID`, `CLIENT_SECRET` o `TENANT_ID`
- Estos valores NO deben estar en control de versiones
- Considera guardarlos en variables de entorno (.env file)
