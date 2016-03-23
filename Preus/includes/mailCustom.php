<div id="fancyMail" style="display: none">
	<form id="redactarMail" action="#" method="post" name="redactarMail">
		<h2>Redactar email personalizado</h2>
		<label for="asunto">Asunto del email</label>
		<input id="asunto" class="txt" name="asunto" />
		
		<table class="mailCustom">
			<tbody>
				<tr>
					<td>
						<label for="msg">Mensaje</label>
					</td>
					<td>
						Agregar palabras clave (al final del texto):
					</td>
				</tr>
				<tr>
					<td>
						<textarea id="msg" class="txtarea" name="msg"></textarea>	
					</td>
					<td class="palabrasClave">
						<span data-clave="<nombreAlumno>">- Nombre Alumno (sin apellido)</span>
						<span data-clave="<apellidosAlumno>">- Apellidos del alumno</span>
						<span data-clave="<nombreMama>">- Nombre Mamá</span>
						<span data-clave="<nombrePapa>">- Nombre Papá</span>
						<span data-clave="<elella>">- Él o ella</span>
						<span data-clave="<oa>">- Terminar palabra en "o" o en "a" (alumn"o"/alumn"a")</span>
						<span data-clave="<firma>">- Firma</span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<button id="sendCustomMail" class="boton">Enviar</button>
		<button id="cancelCustomMail" class="boton">Cancelar</button>
	</form>
</div>