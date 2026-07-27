


    <div class="container">

        <div class="card-header">
            <h4>Employee Exit Form</h4>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Employee Name</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $employee->name }}"
                           readonly>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Employee ID</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $employee->emp_id }}"
                           readonly>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Department</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $employee->department->name }}"
                           readonly>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Designation</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $employee->designation->name }}"
                           readonly>
                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label>Type of Separation</label>

                    <select name="separation_type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="Resignation">Resignation</option>
                        <option value="Termination">Termination</option>
                        <option value="End of Contract">End of Contract</option>
                        <option value="Absconding">Absconding</option>
                        <option value="Others">Others</option>
                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label>Reason for Leaving</label>

                    <select name="reason" class="form-select" required>
                        <option value="">Select</option>
                        <option value="Career Growth">Career Growth</option>
                        <option value="Better Opportunity">Better Opportunity</option>
                        <option value="Compensation & Benefits">Compensation & Benefits</option>
                        <option value="Work Environment">Work Environment</option>
                        <option value="Personal Reasons">Personal Reasons</option>
                        <option value="Relocation">Relocation</option>
                        <option value="Health">Health</option>
                        <option value="Higher Studies">Higher Studies</option>
                        <option value="Others">Others</option>
                    </select>

                </div>

                <div class="col-md-12 mb-3">

                    <label>Additional Comments</label>

                    <textarea class="form-control"
                              rows="3"
                              name="comments"></textarea>

                </div>

                <div class="col-md-6 mb-3">

                    <label>What did you like most?</label>

                    <textarea class="form-control"
                              rows="3"
                              name="liked" required></textarea>

                </div>

                <div class="col-md-6 mb-3">

                    <label>What could we improve?</label>

                    <textarea class="form-control"
                              rows="3"
                              name="improve" required></textarea>

                </div>

                <div class="col-md-6 mb-3">

                    <label>Overall Experience</label>

                    <select class="form-select"
                            name="experience" required>

                        <option>Excellent</option>

                        <option>Good</option>

                        <option>Average</option>

                        <option>Poor</option>

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label>Recommend Company?</label>

                    <br>

                    <input type="radio"
                           name="recommend"
                           value="Yes" required> Yes

                    &nbsp;&nbsp;

                    <input type="radio"
                           name="recommend"
                           value="No" required> No

                </div>

                <div class="col-md-12 mb-3">

                    <label>Suggestions</label>

                    <textarea class="form-control"
                              rows="3"
                              name="suggestions"></textarea>

                </div>

                <div class="col-md-6 mb-3">

                    <label>Handover Completed?</label>

                    <br>

                    <input type="radio"
                           name="handover"
                           value="Yes" required> Yes

                    &nbsp;&nbsp;

                    <input type="radio"
                           name="handover"
                           value="No" required> No

                </div>

                <div class="col-md-6 mb-3">

                    <label>Handover Details</label>

                    <textarea class="form-control"
                              rows="3"
                              name="handover_details"></textarea>

                </div>

                <div class="col-md-12">

                    <div class="form-check">

                        <input class="form-check-input"
                               type="checkbox"
                               required
                               name="declaration" required>

                        <label class="form-check-label">

                            I confirm that all company assets have been returned and no dues are pending.

                        </label>

                    </div>

                </div>

                <div class="col-md-6 mt-3">

                    <label>Upload Signature</label>

                    <input type="file"
                           class="form-control"
                           name="signature"
                           accept="image/*" required>

                </div>

            </div>

        </div>

        <div class="card-footer text-end">

            <button type="reset"
                    class="btn btn-secondary">

                Cancel

            </button>

            <button type="submit"
                    class="btn btn-primary" id="submitExitForm">

                Submit Exit Form

            </button>

        </div>

    </div>



<script>
   $(document).on('click', '#submitExitForm', function () {

    let formData = new FormData();

    formData.append('_token', $('input[name="_token"]').val());
    formData.append('separation_type', $('select[name="separation_type"]').val());
    formData.append('reason', $('select[name="reason"]').val());
    formData.append('comments', $('textarea[name="comments"]').val());
    formData.append('liked', $('textarea[name="liked"]').val());
    formData.append('improve', $('textarea[name="improve"]').val());
    formData.append('experience', $('select[name="experience"]').val());
    formData.append('recommend', $('input[name="recommend"]:checked').val() ?? '');
    formData.append('suggestions', $('textarea[name="suggestions"]').val());
    formData.append('handover', $('input[name="handover"]:checked').val() ?? '');
    formData.append('handover_details', $('textarea[name="handover_details"]').val());
    formData.append('declaration', $('input[name="declaration"]').is(':checked') ? 1 : 0);

    let signature = $('input[name="signature"]')[0].files[0];
    if (signature) {
        formData.append('signature', signature);
    }

    $.ajax({
        url: "{{ route('employee.exitform.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        beforeSend: function () {
            $('#submitExitForm').prop('disabled', true);
        },

        success: function (res) {

            Swal.fire({
                icon: 'success',
                title: 'Submitted',
                text: res.message
            });

            $('#exitForm')[0].reset();

        },

        error: function (xhr) {

            if (xhr.responseJSON?.submitted) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Already Submitted',
                    text: xhr.responseJSON.message
                });
                return;
            }

            if (xhr.responseJSON?.errors) {

                let errors = '';

                $.each(xhr.responseJSON.errors, function (key, value) {
                    errors += value[0] + '<br>';
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errors
                });

                return;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong.'
            });

        },

        complete: function () {
            $('#submitExitForm').prop('disabled', false);
        }

    });

});
</script>