() => (
  <label for={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher+"-element" }>
    { this.properties.label }
    <span className={ "red-text" + ( this.properties.required ? "" : " hide" ) }>&nbsp;*</span>
  </label>
);
