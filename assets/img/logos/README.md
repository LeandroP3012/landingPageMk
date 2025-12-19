# Carpeta de Logos para el Slider

## Instrucciones

Coloca aquí las imágenes de los logos de tus clientes/marcas.

### Nombres de archivo:
- logo-1.png
- logo-2.png
- logo-3.png
- ... hasta logo-10.png (o los que necesites)

### Recomendaciones:
- **Formato**: PNG con fondo transparente
- **Tamaño**: Altura máxima de 100-150px (el slider ajustará automáticamente)
- **Color**: Preferiblemente logos en color oscuro/negro (el CSS los convertirá a blanco automáticamente)
- **Peso**: Optimiza las imágenes para web (máximo 100KB por logo)

### Personalización:

Si quieres agregar más o menos logos, edita el archivo:
`components/logo-slider.php`

Para cambiar la velocidad de la animación, edita en:
`assets/css/styles.css` la línea:
```css
animation: slide-logos 30s linear infinite;
```

Cambia `30s` por el tiempo que desees (mayor = más lento, menor = más rápido)
