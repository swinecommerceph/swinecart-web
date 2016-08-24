<form class="col s12">
  <div class="input-field col s12">
    <select>
      <option value="" disabled selected>Choose slide to edit</option>
      <option value="1">Slide 1</option>
      <option value="2">Slide 2</option>
      <option value="3">Slide 3</option>
    </select>
    <label>Carousel Slide</label>
  </div>

  <div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s6">
          <input id="input_text" type="text">
          <label for="input_text">Title</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <textarea id="textarea1" class="materialize-textarea" length="120"></textarea>
          <label for="textarea1">Content</label>
        </div>
      </div>
    </form>
  </div>

  <div class="file-field input-field">
    <div class="btn">
      <span>File</span>
      <input type="file">
    </div>
    <div class="file-path-wrapper">
      <input class="file-path validate" type="image">
    </div>
  </div>
</form>
