() => (
  <label
   for={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
   class={ 
    (this.properties.hasOwnProperty("primary") && this.properties.primary) ||
    (this.properties.hasOwnProperty("valueIsPrefilled") && this.properties.valueIsPrefilled)
      ? " active"
      : "" } >
    { this.properties.label }
    <span className={ "red-text" + ( this.properties.required ? "" : " hide" ) }>&nbsp;*</span>
  </label>
);
