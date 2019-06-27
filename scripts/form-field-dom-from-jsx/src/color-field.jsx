() => (
  <div>
    <div class="color-field col s12">
      <div class="chosen-color btn" style={{ 
        backgroundColor: this.properties.valueIsPrefilled
          ? "#"+this.properties.value
          : getComputedStyle(document.documentElement).getPropertyValue("--main-color").trim()
      }} ></div>
      <div class="color-input-wrapper">
        <div class="input-field col s12">
          <input
           readonly="readonly"
           disabled="disabled"
           type="text"
           class="active"
           id={ this.properties.formDistinguisher+"-input-"+this.properties.distinguisher }
           autocomplete="off"
           value={
            this.properties.valueIsPrefilled
              ? "#"+this.properties.value
              : getComputedStyle(document.documentElement).getPropertyValue("--main-color").trim()
           } />
          <form-label data-properties={ JSON.stringify(this.properties) } />
          <form-label-helper-span data-properties={ JSON.stringify(this.properties) } />
        </div>
      </div>
    </div>
    <div class="color-picker-modal modal bottom-sheet">
      <div class="modal-content">
        <h3>Color</h3>
        <h5>Choose a color</h5>

        <div class="row">
          { Object.keys(this.properties.colorMap).map(key => (
            <div
             data-category-colors={ this.properties.colorMap[key] }
             data-color={ key }
             style={{
              backgroundColor: "#"+key
             }}
             class="color-swatch col l2 m3 s12" >
            </div>
          )).call(this) }
        </div>
      </div>
    </div>
  </div>
);
