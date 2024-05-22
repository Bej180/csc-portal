<div class="popup">
    <form class="popup-wrapper lg:!max-w-[60%]">
        <div class="popup-header">
            Edit Advisor
        </div>
        <div class="popup-body flex flex-col gap-6" tabindex="-1">
            <fieldset class="border-t border-slate-400/25">
                <legend class="form-title text-center mx-4 opacity-25">Basic Details</legend>
                <div class="md:grid md:grid-cols-2 lg:grid-cols-3 gap-4">

                  <div class="">
                    <div class="form-group local-forms">
                        <label>First Name <span class="text-red-600">*</span></label>
                        <input type="text" class="input" value="Vincent">
                    </div>
                </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>Last Name <span class="text-red-600">*</span></label>
                            <input type="text" class="input" value="Vincent">
                        </div>
                    </div>

                    <div class="">
                      <div class="form-group local-forms">
                          <label>Middle Name</label>
                          <input type="text" class="input" value="Vincent">
                      </div>
                  </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>Gender <span class="text-red-600">*</span></label>
                            <select class="input select" tabindex="-1" aria-hidden="true">
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms calendar-icon">
                            <label>Date Of Birth <span class="text-red-600">*</span></label>
                            <input class="input datetimepicker" type="text" placeholder="29-04-2022">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>Mobile <span class="text-red-600">*</span></label>
                            <input type="text" class="input" value="077 3499 9959">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms calendar-icon">
                            <label>Joining Date <span class="text-red-600">*</span></label>
                            <input class="input datetimepicker" type="text" placeholder="29-04-2022">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>Qualification <span class="text-red-600">*</span></label>
                            <input class="input" type="text" value="Bachelor of Engineering">
                        </div>
                    </div>
                    
                </div>
              </fieldset>







            <fieldset class="border-t border-slate-400/25">
                <legend class="form-title text-center mx-4 opacity-25">Login Details</legend>
                <div class="md:grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                   
                    <div class="">
                        <div class="form-group local-forms">
                            <label>Email ID <span class="text-red-600">*</span></label>
                            <input type="email" class="input" value="vincent20@gmail.com">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>Password <span class="text-red-600">*</span></label>
                            <input type="password" class="input" value="">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>Repeat Password <span class="text-red-600">*</span></label>
                            <input type="password" class="input" value="">
                        </div>
                    </div>



                </div>
            </fieldset>
            <fieldset class="border-t border-slate-400/25">
                <legend class="form-title text-center mx-4 opacity-25">Address</legend>
                <div class="md:grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="col-12 ">
                        <div class="form-group local-forms">
                            <label>Address <span class="text-red-600">*</span></label>
                            <input type="text" class="input" value="3979 Ashwood Drive">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>City <span class="text-red-600">*</span></label>
                            <input type="text" class="input" value="Omaha">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>State <span class="text-red-600">*</span></label>
                            <input type="text" class="input" value="Omaha">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>Zip Code <span class="text-red-600">*</span></label>
                            <input type="text" class="input" value="3979">
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group local-forms">
                            <label>Country <span class="text-red-600">*</span></label>
                            <input type="text" class="input" value="USA">
                        </div>
                    </div>

                </div>
            </fieldset>

          </div>
          <div class="popup-footer">
            
            <button type="type"  ng-click="stopEditing()" class="btn btn-secondary">Cancel</button>
              <button type="submit" class="btn btn-primary">Update</button>
          </div>
    </form>
</div>