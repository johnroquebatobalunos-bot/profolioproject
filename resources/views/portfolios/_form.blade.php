<form class="portfolio-form" action="{{ $action }}" method="post" enctype="multipart/form-data">
    @csrf
    @if ($method === 'put') @method('PUT') @endif

    <section class="form-section" aria-labelledby="personal-details-title">
        <div class="form-section-index">01</div>
        <div class="form-section-content">
            <div class="form-section-heading"><h2 id="personal-details-title" tabindex="-1">Start with you</h2><p>The details people use to find and remember you.</p></div>
            <div class="form-grid">
                <label class="field field-wide">Full name <span class="required">*</span>
                    <input name="name" value="{{ old('name', $formValues['name'] ?? '') }}" autocomplete="name" required maxlength="120" placeholder="e.g. Jordan Lee">
                    @error('name')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label class="field">Email address <span class="required">*</span>
                    <input type="email" name="email" value="{{ old('email', $formValues['email'] ?? '') }}" autocomplete="email" required placeholder="you@example.com">
                    @error('email')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label class="field">Contact number
                    <input type="tel" name="phone" value="{{ old('phone', $formValues['phone'] ?? '') }}" autocomplete="tel" placeholder="+1 555 0100">
                    @error('phone')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label class="field field-wide">Address
                    <input name="address" value="{{ old('address', $formValues['address'] ?? '') }}" autocomplete="street-address" placeholder="City, country">
                    @error('address')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label class="field field-wide">Profile picture
                    @if (!empty($formValues['photo_url']))<span class="current-photo"><img src="{{ $formValues['photo_url'] }}" alt="Current profile picture"> Current picture</span>@endif
                    <input class="file-input" type="file" name="profile_picture" accept="image/jpeg,image/png,image/webp">
                    <span class="field-hint">JPG, PNG or WebP · up to 4 MB</span>
                    @error('profile_picture')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label class="field field-wide">About me
                    <textarea name="about" rows="5" maxlength="3000" placeholder="A few lines about what you do, what you care about, and where you want to go.">{{ old('about', $formValues['about'] ?? '') }}</textarea>
                    @error('about')<small class="field-error">{{ $message }}</small>@enderror
                </label>
            </div>
        </div>
    </section>

    <section class="form-section" aria-labelledby="education-title">
        <div class="form-section-index">02</div>
        <div class="form-section-content">
            <div class="form-section-heading"><h2 id="education-title" tabindex="-1">What you've learned</h2><p>Education and the skills you bring to the room.</p></div>
            <div class="form-grid">
                <div class="field field-wide">Education
                    <div class="entry-editor" data-entry-editor data-entry-fields="Qualification|School|Years" data-entry-placeholders="B.A. Graphic Design|Northshore College|2021–2025" data-entry-add-label="Add education">
                        <div class="entry-list" data-entry-list></div>
                        <textarea class="entry-source" name="education_text" data-entry-source rows="3" placeholder="B.A. Graphic Design | Northshore College | 2021–2025">{{ old('education_text', $formValues['education_text'] ?? '') }}</textarea>
                        <div class="entry-controls"><button class="button button-outline" type="button" data-entry-add>Add education <span aria-hidden="true">+</span></button><span class="field-hint">Add each qualification separately.</span></div>
                    </div>
                    @error('education_text')<small class="field-error">{{ $message }}</small>@enderror
                </div>
                <label class="field field-wide">Skills
                    <textarea name="skills_text" rows="3" placeholder="Figma, Brand design, Prototyping, Research">{{ old('skills_text', $formValues['skills_text'] ?? '') }}</textarea>
                    <span class="field-hint">Separate skills with commas or new lines.</span>
                    @error('skills_text')<small class="field-error">{{ $message }}</small>@enderror
                </label>
            </div>
        </div>
    </section>

    <section class="form-section" aria-labelledby="work-title">
        <div class="form-section-index">03</div>
        <div class="form-section-content">
            <div class="form-section-heading"><h2 id="work-title" tabindex="-1">Work worth sharing</h2><p>Projects, experience, and the links that take people further.</p></div>
            <div class="form-grid">
                <div class="field field-wide">Projects
                    <div class="entry-editor" data-entry-editor data-entry-fields="Project name|What you did|Project link" data-entry-placeholders="Field Notes|A digital archive for local stories|https://example.com" data-entry-add-label="Add project">
                        <div class="entry-list" data-entry-list></div>
                        <textarea class="entry-source" name="projects_text" data-entry-source rows="5" placeholder="Project name | What you made and why | https://example.com">{{ old('projects_text', $formValues['projects_text'] ?? '') }}</textarea>
                        <div class="entry-controls"><button class="button button-outline" type="button" data-entry-add>Add project <span aria-hidden="true">+</span></button><span class="field-hint">Add a title, short description, and optional link.</span></div>
                    </div>
                    @error('projects_text')<small class="field-error">{{ $message }}</small>@enderror
                </div>
                <div class="field field-wide">Work experience
                    <div class="entry-editor" data-entry-editor data-entry-fields="Role|Organization|Dates|What you did" data-entry-placeholders="Product designer|Studio North|2023–now|Led brand and digital projects" data-entry-add-label="Add experience">
                        <div class="entry-list" data-entry-list></div>
                        <textarea class="entry-source" name="experience_text" data-entry-source rows="4" placeholder="Designer | Studio North | 2023–now | Led brand and digital projects">{{ old('experience_text', $formValues['experience_text'] ?? '') }}</textarea>
                        <div class="entry-controls"><button class="button button-outline" type="button" data-entry-add>Add experience <span aria-hidden="true">+</span></button><span class="field-hint">Most recent role first works well.</span></div>
                    </div>
                    @error('experience_text')<small class="field-error">{{ $message }}</small>@enderror
                </div>
                <div class="field field-wide">Social, portfolio, or website links
                    <div class="entry-editor" data-entry-editor data-entry-fields="Link name|URL" data-entry-placeholders="LinkedIn|https://linkedin.com/in/yourname" data-entry-add-label="Add link">
                        <div class="entry-list" data-entry-list></div>
                        <textarea class="entry-source" name="links_text" data-entry-source rows="3" placeholder="LinkedIn | https://linkedin.com/in/yourname">{{ old('links_text', $formValues['links_text'] ?? '') }}</textarea>
                        <div class="entry-controls"><button class="button button-outline" type="button" data-entry-add>Add link <span aria-hidden="true">+</span></button><span class="field-hint">Only http:// and https:// links are displayed.</span></div>
                    </div>
                    @error('links_text')<small class="field-error">{{ $message }}</small>@enderror
                </div>
            </div>
        </div>
    </section>

    <div class="form-submit-row">
        <a class="text-link" href="{{ $cancelUrl }}">Cancel</a>
        <button class="button button-dark" type="submit">{{ $submitLabel }} <span aria-hidden="true">↗</span></button>
    </div>
</form>