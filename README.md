<h1>🚧 SRT Subtitle Fixer 🚧 | [ UNDER CONSTRUCTION ]</h1>

<h2>Project Description</h2>
<p>The <strong>SRT Subtitle Fixer</strong> is a web-based application designed to correct character corruption issues in SRT subtitle files, particularly focusing on Turkish characters. The application allows users to upload multiple SRT files, processes these files to fix any corrupted characters, and then provides the corrected files for download in a ZIP archive.</p>

<h2>Features</h2>
<ul>
<li><strong>Batch Processing</strong>: Upload and process multiple SRT files simultaneously.</li>
<li><strong>Character Correction</strong>: Automatically fix corrupted Turkish characters in subtitle files.</li>
<li><strong>Download Processed Files</strong>: Download all processed files in a single ZIP archive.</li>
<li><strong>Clean Uploads</strong>: Option to clean the uploads folder to remove any temporary files.</li>
</ul>

<h2>Getting Started</h2>

<h3>Prerequisites</h3>
<ul>
<li>PHP (version 7.4 or higher)</li>
<li>A web server (e.g., Apache, Nginx)</li>
</ul>

<h3>Installation</h3>
<ol>
<li>Clone the repository to your local machine:
<pre><code>git clone https://github.com/ArhanGoncaliOfficial/Subtitle-App.git</code></pre>
</li>
<li>Navigate to the project directory:
<pre><code>cd Subtitle-App</code></pre>
</li>
<li>Make sure your web server is set up to serve the project directory.</li>
</ol>

<h3>Usage</h3>
<ol>
<li>Open the application in your web browser.</li>
<li>Drag and drop your SRT files into the designated area or click to select files.</li>
<li>Once the files are uploaded, click the "Fix Uploaded Files" button.</li>
<li>After processing, a download link for the ZIP archive containing the corrected files will be provided.</li>
</ol>

<h2>Code Overview</h2>

<h3>PHP Script (<code>subtitle.php</code>)</h3>
<ul>
<li><code>fixSubtitles($filePath)</code>: Function to fix corrupted Turkish characters in the subtitle file.</li>
<li><code>cleanUploadsFolder($folder)</code>: Function to clean the uploads folder.</li>
<li>Handles file uploads, processes each file, and packages the corrected files into a ZIP archive.</li>
</ul>

<h3>HTML and JavaScript (<code>index.html</code> and <code>script.js</code>)</h3>
<ul>
<li>Provides the user interface for uploading and processing subtitle files.</li>
<li>Displays the upload area, processes the files on the client side, and handles form submission.</li>
</ul>

<h2>Acknowledgements</h2>
<p>Made by <b>Arhan Goncalı</b></p>
